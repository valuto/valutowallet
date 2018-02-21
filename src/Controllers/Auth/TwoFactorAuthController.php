<?php

namespace Controllers\Auth;

use Controllers\Controller;
use Models\Flash;
use Models\User;

class TwoFactorAuthController extends Controller
{
    /**
     * Database instance.
     * 
     * @var object
     */
    protected $db;

    /**
     * Verification messages.
     * 
     * @var string
     */
    const VERIFY_NOT_SETUP     = 'VERIFY_NOT_SETUP';
    const VERIFY_INVALID_CODE  = 'VERIFY_INVALID_CODE';
    const VERIFY_INVALID_INPUT = 'VERIFY_INVALID_INPUT';
    const VERIFY_SUCCESSFUL    = 'VERIFY_SUCCESSFUL';

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $mysqli;

        $this->db = $mysqli; 
    }

    /**
     * Generate and show new secret for 2FA.
     * 
     * @return json
     */
    public function store()
    {
        $user   = new User($this->db);
        $secret = $user->createSecret();
        $qrcode = $user->getQRCodeGoogleUrl('Wallet', $secret);

        $_SESSION['secret_key_not_verified'] = $secret;

        return json_encode([
            'secret' => $secret,
            'qrcode' => $qrcode,
        ]);
    }

    /**
     * Verify 2FA code and enable on account if correct.
     * 
     * @return boolean was the code verified and enabled or not?
     */
    public function update()
    {
        $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));

        if (empty($_SESSION['secret_key_not_verified'])) {
            return json_encode([
                'error' => self::VERIFY_NOT_SETUP,
                'newtoken' => $_SESSION['token'],
            ]);
        }

        if (empty($_POST['code'])) {
            return json_encode([
                'error' => self::VERIFY_INVALID_INPUT,
                'newtoken' => $_SESSION['token'],
            ]);
        }

        $user    = new User($this->db);
        $oneCode = (int)$user->getCode($_SESSION['secret_key_not_verified']);

        if ($oneCode !== (int)$_POST['code']) {
            return json_encode([
                'error' => self::VERIFY_INVALID_CODE,
                'newtoken' => $_SESSION['token'],
            ]);
        }

        if ($oneCode === (int)$_POST['code']) {

            $user->enableauth($_SESSION['secret_key_not_verified']);
            $_SESSION['user_2fa'] = 1;
            unset($_SESSION['secret_key_not_verified']);

            return json_encode([
                'success' => self::VERIFY_SUCCESSFUL,
                'newtoken' => $_SESSION['token'],
            ]);

        }
        
    }

    /**
     * Disable 2 factor auth
     */
    public function destroy()
    {
        $user    = new User($this->db);
        $disauth = $user->disauth();

        $_SESSION['user_2fa'] = 0;
        unset($_SESSION['secret_key_not_verified']);

        return $disauth;
    }

}