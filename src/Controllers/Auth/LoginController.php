<?php

namespace Controllers\Auth;

use Controllers\Controller;
use Models\User;
use Models\Flash;

class LoginController extends Controller
{
    /**
     * Login
     * 
     * @return void
     */
    public function store()
    {
        global $lang, $error, $fullname, $mysqli;

        $error = array('type' => "none", 'message' => "");

        $user = new User($mysqli);

        $result = $user->logIn($_POST['username'], $_POST['password'], $_POST['auth']);
        if (!is_array($result)) {

            Flash::save('error', $result);
            return redirect('');

        } else {
            $user->setAuthSession($result);
            if (is_null($result['password'])) {
                Flash::save('showNotice', lang('WALLET_PLEASE_UPDATE_PASSWORD'));
            }
            $mysqli->close();
            return redirect('');
        }
    }

    /**
     * Logout
     * 
     * @return void
     */
    public function destroy()
    {
        session_destroy();
        return redirect('');
    }
}