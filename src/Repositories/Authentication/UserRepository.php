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
use Entities\Authentication\UserEntity;
use Models\User;

/**
 * User repository.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * The user model instance.
     * 
     * @var User
     */
    protected $user;

    /**
     * Instantiate repository.
     */
    public function __construct()
    {
        global $mysqli;

        $this->user = new User($mysqli);
    }

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
        $user = $this->user->getUserByUsername($username);

        if ( ! $this->user->isActive($user)) {
            return false;
        }

        if ( ! $this->user->verifyPasswordMatch($password, $user)) {
            return false;
        }

        return new UserEntity($user);
    }
}