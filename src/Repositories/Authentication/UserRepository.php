<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Repositories\Authentication;

use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * User repository.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Get a user entity.
     *
     * @param string                $username
     * @param string                $password
     * @param string                $grantType    The grant type used
     * @param ClientEntityInterface $clientEntity
     *
     * @return UserEntityInterface
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    )
    {
        foreach (config('api.clients') as $client) {

            if (empty($client['client_id'])) {
                continue;
            }

            if (empty($client['client_secret'])) {
                continue;
            }

            if ($username === $client['client_id'] && $password === $client['client_secret']) {
                return new UserEntity();
            }

        }

        return;
    }
}