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
            $username                    = $mysqli->real_escape_string(strip_tags($_POST['username']));
            $_SESSION['user_session']    = $username;
            $_SESSION['user_supportpin'] = "Please relogin for Support Key";
            
            Flash::save('showdisclaimer', true);
            redirect('');
        }
    }
}