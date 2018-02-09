<?php

namespace Controllers\Auth;

use Controllers\Controller;
use Models\Flash;
use Models\User;

class TwoFactorAuthController extends Controller
{

    /**
     * Enable 2 factor auth
     */
    public function store()
    {
        global $mysqli;

        $user   = new User($mysqli);
        $secret = $user->createSecret();
        $gen    = $user->enableauth();
        return $gen;
    }

    /**
     * Disable 2 factor auth
     */
    public function destroy()
    {
        global $mysqli;

        $user    = new User($mysqli);
        $disauth = $user->disauth();
        return $disauth;
    }

}