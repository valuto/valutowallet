<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Repositories\Authentication;

use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Entities\Authentication\ScopeEntity;
use Models\Role;
use Models\User;
use Exception;

/**
 * Scope repository.
 */
class ScopeRepository implements ScopeRepositoryInterface
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
     * Return information about a scope.
     *
     * @param string $scopeIdentifier The scope identifier
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        $scopes = (new Role())->select('role_id');

        if ( ! in_array($scopeIdentifier, $scopes)) {
            return false;
        }

        $scope = new ScopeEntity();
        $scope->setIdentifier($scopeIdentifier);

        return $scope;
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string                 $grantType
     * @param ClientEntityInterface  $clientEntity
     * @param null|string            $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    )
    {
        if ($grantType === 'client_credentials') {

            $allRoles = (new Role())->select('role_id');

            // @TODO extend filtering of roles based on client.

            return $this->intersectScopes($scopes, $allRoles);

        } else if ($grantType === 'password') {

            $user   = $this->user->getUserById($userIdentifier);
            $roles  = $this->user->getRoles($user);

            return $this->intersectScopes($scopes, $roles ? array_column($roles, 'role_id') : []);

        } else {

            throw new Exception('Invalid grant type.');

        }
    }

    /**
     * Only return scopes that are both present in $requested and $valid.
     * 
     * @param  array $requested  the OAuth2 scopes array containing ScopeEntity-objects.
     * @param  array $valid      the scopes (roles) valid for the client/user.
     * @return array
     */
    protected function intersectScopes($requested, $valid)
    {
        foreach ($requested as $key => $scope) {
            if ( ! in_array($scope->getIdentifier(), $valid)) {
                unset($requested[$key]);
            }
        }
        
        return $requested;
    }
}