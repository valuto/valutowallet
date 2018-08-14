<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;
use Models\Flash;
use Services\Tiers\TierLevel;
use Repositories\Database\UserRepository;

class UserProfileController extends Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $mysqli;

        $this->user = new User($mysqli);
        $this->userRepository = new UserRepository($mysqli);

        parent::__construct();
    }

    /**
     * Update user profile.
     * 
     * @return string JSON response data.
     */
    public function update(ServerRequestInterface $request)
    {
        $userId = $_SESSION['user_id'];
        $params = $_POST;

        // Invalid e-mail.
        if (! empty($params['email']) && ! filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
            return json_encode([
                'status' => 'error',
                'message' => lang('WALLET_PARTICULARS_UPDATE_ERROR'),
            ]);
        }

        $user = $this->user->getUserById($userId);

        $particulars = [
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'address_1' => $params['address_1'],
            'address_2' => $params['address_2'],
            'zip_code' => $params['zip_code'],
            'city' => $params['city'],
            'state' => $params['state'],
            'country' => $params['country'],
            'email' => $params['email'],
            'phone_number' => $params['phone_number'],
        ];

        $result = $this->userRepository->updateProfile($userId, $particulars);

        if ($user['email'] !== $params['email']) {
            $this->userRepository->resetEmailConfirmation($userId);
        }

        if ($user['phone_number'] !== $params['phone_number']) {
            $this->userRepository->resetPhoneNumberConfirmation($userId);
        }

        // Refetch after update.
        $user = $this->user->getUserById($userId);

        $this->userRepository->updateTier($userId, TierLevel::determine($user));

        if ($result) {

            return json_encode([
                'status' => 'success',
                'message' => lang('WALLET_PARTICULARS_UPDATE_SUCCESSFUL'),
            ]);

        } else {

            return json_encode([
                'status' => 'error',
                'message' => lang('WALLET_PARTICULARS_UPDATE_ERROR'),
            ]);

        }
    }
}