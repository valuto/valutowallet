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
            $_SESSION['user_session']    = $result['username'];
            $_SESSION['user_admin']      = $result['admin'];
            $_SESSION['user_supportpin'] = $result['supportpin'];
            $_SESSION['user_id']         = $result['id'];
            $_SESSION['user_2fa']        = $result['authused'];
            if (is_null($result['password'])) {
                Flash::save('promptupdatepw', '1');
            }
            return redirect('');
            $mysqli->close();
            die;
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