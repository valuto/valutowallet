<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Controllers\Controller;
use Models\User;
use Models\Flash;

class UserProfileController extends Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $mysqli;

        $this->user = new User($mysqli);

        parent::__construct();
    }

    /**
     * Update user profile.
     * 
     * @return string JSON response data.
     */
    public function update(ServerRequestInterface $request)
    {
        $params = $_POST;

        // Invalid e-mail.
        if (! empty($params['email']) && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode([
                'status' => 'error',
                'message' => lang('WALLET_PARTICULARS_UPDATE_ERROR'),
            ]);
        }

        $user = $this->user->getUserById($_SESSION['user_session']);

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
        ];

        $result = $this->user->updateUserProfile($_SESSION['user_id'], $particulars);

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