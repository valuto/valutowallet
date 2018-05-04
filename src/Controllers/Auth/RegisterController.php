<?php

namespace Controllers\Auth;

use Controllers\Controller;
use Models\Flash;
use Models\User;

class RegisterController extends Controller
{
    public function store()
    {
        global $mysqli;

        $error = array('type' => "none", 'message' => "");
    
        $user = new User($mysqli);

        $result = $user->add($_POST['username'], $_POST['password'], $_POST['confirmPassword']);

        if ($result !== true) {
            
            Flash::save('error', $result);
            return redirect('');

        } else {

            $userId   = $mysqli->insert_id;
            $username = $mysqli->real_escape_string(strip_tags($_POST['username']));
        
            $user->setAuthSession([
                'username' => $username,
                'admin' => 0,
                'supportpin' => lang('WALLET_REGISTER_RELOGIN_FOR_SUPPORTPIN'),
                'id' => $userId,
                'authused' => 0,
                'uses_old_account_identifier' => 0,
            ]);

            Flash::save('showdisclaimer', true);
            redirect('');

        }
    }
}