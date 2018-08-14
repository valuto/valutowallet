<?php

namespace Controllers\Kyc;

use Psr\Http\Message\ServerRequestInterface;
use Models\User;
use Traits\RenderMessage;
use Services\Tiers\KycCheck;
use Controllers\Controller;
use Repositories\Database\UserRepository;

class SkipController extends Controller
{
    use RenderMessage;

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
        $this->userRepository = new UserRepository($this->mysqli);
    }

    /**
     * User skips KYC verification.
     * 
     * @return 
     */
    public function store()
    {
        $this->userRepository->skipKycReminder($_SESSION['user_id']);

        $_SESSION['kyc_reminder_skipped'] = time();

        return redirect('/');
    }
}