<?php

namespace Controllers\Auth;

use Controllers\Controller;
use Models\User;

class PasswordController extends Controller
{

    /**
     * Change password
     */
    public function update()
    {
        global $mysqli;

        $user            = new User($mysqli);
        $json['success'] = false;
        if (empty($_POST['oldpassword']) || empty($_POST['newpassword']) || empty($_POST['confirmpassword'])) {
            $json['message'] = "You have to fill all the fields";
        } elseif ($_POST['token'] != $_SESSION['token']) {
            $json['message']   = "Tokens do not match";
            $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));
            $json['newtoken']  = $_SESSION['token'];
        } else {
            $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));
            $json['newtoken']  = $_SESSION['token'];
            $result            = $user->updatePassword($_SESSION['user_session'], $_POST['oldpassword'], $_POST['newpassword'], $_POST['confirmpassword']);
            if ($result === true) {
                $json['success'] = true;
                $json['message'] = "Password updated successfully.";
            } else {
                $json['message'] = $result;
            }
        }

        return json_encode($json);
    }

}