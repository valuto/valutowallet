<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Repositories\Authentication;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Entities\Authentication\ClientEntity;
use Exceptions\ClientNotFoundException;
use Exceptions\ClientSecretIncorrectException;
use Exceptions\GrantTypeNotAllowedException;

/**
 * Client storage repository.
 */
class ClientRepository implements ClientRepositoryInterface
{
    /**
     * Get a client.
     *
     * @param string      $clientIdentifier   The client's identifier
     * @param null|string $grantType          The grant type used (if sent)
     * @param null|string $clientSecret       The client's secret (if sent)
     * @param bool        $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $clients = config('api', 'clients');

        // Check if client is registered
        if (array_key_exists($clientIdentifier, $clients) === false) {
            throw new ClientNotFoundException('Client not found.', 0, 'client_not_found', 401);
        }

        if (
            $mustValidateSecret === true
            && $clients[$clientIdentifier]['is_confidential'] === true
            && password_verify($clientSecret, $clients[$clientIdentifier]['secret']) === false
        ) {
            throw new ClientSecretIncorrectException('Client secret incorrect.', 0, 'client_secret_incorrect', 401);
        }

        if ( ! in_array($grantType, $clients[$clientIdentifier]['allowed_grant_types'])) {
            throw new GrantTypeNotAllowedException('Client not allowed to use this grant type.', 0, 'wrong_grant_type', 401);
        }

        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName($clients[$clientIdentifier]['name']);
        $client->setRedirectUri($clients[$clientIdentifier]['redirect_uri']);
        return $client;
    }
}