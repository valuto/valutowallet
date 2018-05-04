<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Repositories\Authentication;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use Entities\Authentication\AccessTokenEntity;

/**
 * Access token repository.
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * Database instance.
     * 
     * @var MySQLi
     */
    protected $mysqli;

    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        global $mysqli;
        $this->mysqli = $mysqli;
    }

    /**
     * Create a new access token
     *
     * @param ClientEntityInterface  $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed                  $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        $accessToken->setUserIdentifier($userIdentifier);
        return $accessToken;
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO api_access_tokens (token, client_id, created_at, revoked_at) VALUES(?, ?, NOW(), NULL)");

        if (!$stmt) {
            throw new \Exception('Could not store access token.');
        }

        $identifer = $accessTokenEntity->getIdentifier();
        $clientIdentifier = $accessTokenEntity->getClient()->getIdentifier();

        $stmt->bind_param('ss', $identifer, $clientIdentifier);
        $result = $stmt->execute();
        $stmt->close();

        if (!$result) {
            throw new \Exception('Could not store access token.');
        }
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId)
    {
        $stmt = $this->mysqli->prepare("UPDATE api_access_tokens SET revoked_at = NOW() WHERE token=?");

        if (!$stmt) {
            throw new \Exception('Could not revoke access token.');
        }

        $stmt->bind_param('s', $tokenId);
        $result = $stmt->execute();
        $stmt->close();

        if (!$result) {
            throw new \Exception('Could not revoke access token.');
        }
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM api_access_tokens WHERE token=?');

        if (!$stmt) {
            throw new \Exception('Could not check access token status.');
        }

        $stmt->bind_param('s', $tokenId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        $accessToken = $result->fetch_assoc();

        return ! $accessToken || ! is_null($accessToken['revoked_at']);
    }
}