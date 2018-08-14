<?php

namespace Controllers\Kyc;

use Models\User;
use Services\Tiers\KycCheck;
use Controllers\Controller;

class StatusController extends Controller
{
    /**
     * The database instance.
     * 
     * @var MySQLi
     */
    protected $mysqli;

    /**
     * Construct controller with dependencies.
     * 
     * @return void
     */
    public function __construct()
    {
        global $mysqli;

        $this->mysqli = $mysqli;
    }

    /**
     * Show KYC status for user.
     * 
     * @return 
     */
    public function show()
    {
        $user = (new User($this->mysqli))->getUserById($_SESSION['user_id']);

        return json('verified', (int) KycCheck::isVerified($user));
    }
}