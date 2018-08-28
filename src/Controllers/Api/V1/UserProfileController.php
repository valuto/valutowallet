<?php

namespace Controllers\Api\V1;

use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;
use Services\Tiers\TierLevel;
use Repositories\Database\UserRepository;

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

        $this->userRepository = new UserRepository($this->mysqli);

        $params = $_POST;

        // Invalid e-mail.
        if (isset($params['email']) && ! empty($params['email']) && ! filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            return json_encode([
                'status'  => 'error',
                'error'   => 'invalid_email',
                'message' => 'The entered e-mail is not valid.',
            ]);
        }

        $user = $this->user->getUserById($this->userId);

        if ( ! $user) {
            return $this->userNotFound();
        }

        $fields = [
            'first_name',
            'last_name',
            'address_1',
            'address_2',
            'zip_code',
            'city',
            'state',
            'country',
            'email',
            'phone_number',
        ];

        $stored = array_intersect_key($user, array_flip($fields));
        $fromRequest = array_intersect_key($params, array_flip($fields));

        // Update only values from $request, that were actually provided.
        $particulars = array_merge($stored, $fromRequest);

        $result = $this->userRepository->updateProfile($this->userId, $particulars);

        if (isset($params['email']) && $user['email'] !== $params['email']) {
            $this->userRepository->resetEmailConfirmation($this->userId);
        }

        if (isset($params['phone_number']) && $user['phone_number'] !== $params['phone_number']) {
            $this->userRepository->resetPhoneNumberConfirmation($this->userId);
        }

        // Refetch after update.
        $user = $this->user->getUserById($this->userId);

        $this->userRepository->updateTier($this->userId, TierLevel::determine($user));

        if ($result) {

            return json('status', 'success');

        } else {

            return json_encode([
                'status'  => 'error',
                'error'   => 'update_failed',
                'message' => 'User update failed unexpectedly.',
            ]);

        }
    }

}