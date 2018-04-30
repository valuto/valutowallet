<?php

namespace Controllers\Api\V1\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use Repositories\Authentication\AccessTokenRepository;
use Repositories\Authentication\ClientRepository;
use Repositories\Authentication\ScopeRepository;
use Controllers\Controller;
use Models\Flash;
use Models\User;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Router\Request;

class AccessTokenController extends Controller
{
    /**
     * Create access token.
     * 
     * @return 
     */
    public function store()
    {
        // Init our repositories
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface

        // Path to public and private keys
        if (config('api', 'private_key_passphrase')) {
            $privateKey = new CryptKey(config('api', 'private_key_path'), config('api', 'private_key_passphrase'));
        } else {
            $privateKey = config('api', 'private_key_path');
        }

        // Setup the authorization server
        $server = new \League\OAuth2\Server\AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            config('api', 'encryption_key')
        );

        // Enable the client credentials grant on the server
        $server->enableGrantType(
            new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
            new \DateInterval(config('api', 'access_token_expiration')) // access tokens expiration time
        );

        $response = new Response();
        $request  = ServerRequest::fromGlobals();

        try {
        
            // Try to respond to the request
            return $server->respondToAccessTokenRequest($request, $response)->getBody();
            
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
        
            // All instances of OAuthServerException can be formatted into a HTTP response
            return $exception->generateHttpResponse($response)->getBody();
            
        }
    }
}