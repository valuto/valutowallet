<?php

namespace Repositories\Authentication;

use League\OAuth2\Server\Grant\PasswordGrant;
use Repositories\Authentication\AccessTokenRepository;
use Repositories\Authentication\RefreshTokenRepository;
use Repositories\Authentication\ClientRepository;
use Repositories\Authentication\UserRepository;
use Repositories\Authentication\ScopeRepository;

use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\CryptTrait;

use Entities\Authentication\UserEntity;

use Models\User;
use DateInterval;

class AccessTokenWithoutHttpRepository extends PasswordGrant
{
    use CryptTrait;

    public function issue()
    {
        // Validate request
        /*$client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());*/

        global $mysqli;
        $this->user = new User($mysqli);

        // Path to public and private keys
        if (config('api', 'private_key_passphrase')) {
            $privateKey = new CryptKey(config('api', 'private_key_path'), config('api', 'private_key_passphrase'));
        } else {
            $privateKey = new CryptKey(config('api', 'private_key_path'));
        }

        $this->setAccessTokenRepository(new AccessTokenRepository);

        $client = (new ClientRepository())->getClientEntity(
            'vlumarketusers',
            $this->getIdentifier(),
            env('API_VLUMARKET_USERS_CLIENT_SECRET'),
            true
        );

    
        $user = new UserEntity($this->user->getUserByUsername($_SESSION['user_session']));

        $accessTokenTTL = new DateInterval('PT1H');

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), []);
        $refreshToken = $this->issueRefreshToken($accessToken);
        
        $accessTokenJWT = $accessToken->convertToJWT($privateKey);

        /*$refreshToken = $this->encrypt(
            json_encode(
                [
                    'client_id'        => $accessToken->getClient()->getIdentifier(),
                    'refresh_token_id' => $refreshToken->getIdentifier(),
                    'access_token_id'  => $accessToken->getIdentifier(),
                    'scopes'           => $accessToken->getScopes(),
                    'user_id'          => $accessToken->getUserIdentifier(),
                    'expire_time'      => $refreshToken->getExpiryDateTime()->getTimestamp(),
                ]
            )
        );*/

        $expireDateTime = $accessToken->getExpiryDateTime()->getTimestamp() - (new \DateTime())->getTimestamp();

        return json_encode([
            'token_type' => 'Bearer',
            'expires_in' => $expireDateTime,
            'access_token' => (string)$accessTokenJWT,
        ]);
    }
    
}