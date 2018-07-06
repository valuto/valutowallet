<?php

namespace Controllers\Api\V1\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\CryptKey;
use Repositories\Authentication\AccessTokenRepository;
use Repositories\Authentication\RefreshTokenRepository;
use Repositories\Authentication\ClientRepository;
use Repositories\Authentication\UserRepository;
use Repositories\Authentication\ScopeRepository;
use Controllers\Controller;
use Models\Flash;
use Models\User;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Router\Request;
use DateInterval;

class AccessTokenController extends Controller
{
    /**
     * The server object.
     * 
     * @var AuthorizationServer
     */
    protected $server;

    /**
     * Create access token.
     * 
     * @return 
     */
    public function store()
    {
        // Path to public and private keys
        if (config('api', 'private_key_passphrase')) {
            $privateKey = new CryptKey(config('api', 'private_key_path'), config('api', 'private_key_passphrase'));
        } else {
            $privateKey = config('api', 'private_key_path');
        }

        // Setup the authorization server
        $this->server = new AuthorizationServer(
            new ClientRepository(),
            new AccessTokenRepository(),
            new ScopeRepository(),
            $privateKey,
            config('api', 'encryption_key')
        );

        // Enable the client credentials grant on the server
        $this->enableClientCredentialsGrant();
        
        // Enable the password grant on the server
        $this->enablePasswordGrant();
        
        // Enable the refresh token grant on the server
        $this->enableRefreshTokenGrant();
        
        $response = new Response();
        $request  = ServerRequest::fromGlobals();

        try {
        
            // Try to respond to the request
            return $this->server->respondToAccessTokenRequest($request, $response)->getBody();
            
        } catch (OAuthServerException $exception) {
        
            // All instances of OAuthServerException can be formatted into a HTTP response
            return $exception->generateHttpResponse($response)->getBody();
            
        }
    }

    /**
     * Enable client credentials grant.
     * 
     * @return void
     */
    protected function enableClientCredentialsGrant()
    {
        $this->server->enableGrantType(
            new ClientCredentialsGrant(),
            new DateInterval(config('api', 'access_token_expiration')) // access tokens expiration time
        );
    }

    /**
     * Enable password grant.
     * 
     * @return void
     */
    protected function enablePasswordGrant()
    {
        $passwordGrant = new PasswordGrant(
            new UserRepository(),           // instance of UserRepositoryInterface
            new RefreshTokenRepository()    // instance of RefreshTokenRepositoryInterface
        );
        $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

        // Enable the password grant on the server with a token TTL of 1 hour
        $this->server->enableGrantType(
            $passwordGrant,
            new DateInterval('PT1H') // access tokens will expire after 1 hour
        );
    }
    
    /**
     * Enable refresh token grant.
     * 
     * @return void
     */
    protected function enableRefreshTokenGrant()
    {
        // Enable the refresh token grant on the server
        $grant = new RefreshTokenGrant(new RefreshTokenRepository());
        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // The refresh token will expire in 1 month
        $this->server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // The new access token will expire after 1 hour
        );
    }

}