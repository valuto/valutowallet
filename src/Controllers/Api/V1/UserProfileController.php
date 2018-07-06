<?php

namespace Controllers\Api\V1;

use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;

class UserProfileController extends UserApiController
{
    /**
     * Get user profile.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function show(ServerRequestInterface $request)
    {
        $this->validateUserID($request);
        
        $user = $this->user->getUserById($this->userId);
        
        if ( ! $user) {
            return $this->userNotFound();
        }

        return json_encode([
            'status' => 'success',
            'profile' => [
                'id' => $user['id'],
                'date' => $user['date'],
                'ip' => $user['ip'],
                'username' => $user['username'],
                'authused' => $user['authused'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'address_1' => $user['address_1'],
                'address_2' => $user['address_2'],
                'zip_code' => $user['zip_code'],
                'city' => $user['city'],
                'country_code' => $user['country_code'],
                'state' => $user['state'],
                'email' => $user['email'],
                'tier_level' => $user['tier_level'],
            ],
        ]);
    }
    
    /**
     * Update user profile.
     * 
     * @param ServerRequestInterface  $request  the request object.
     * @return 
     */
    public function update(ServerRequestInterface $request)
    {
        $this->validateUserID($request);
        
        $user = $this->user->getUserById($this->userId);
        
        // @TODO

        return json('status', 'success');
    }

}