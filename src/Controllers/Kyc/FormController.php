<?php

namespace Controllers\Kyc;

use Psr\Http\Message\ServerRequestInterface;
use Models\User;
use Traits\RenderMessage;
use Services\Tiers\KycCheck;
use Controllers\Controller;

class FormController extends Controller
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
    }

    /**
     * Show KYC form for user.
     * 
     * @return 
     */
    public function show()
    {
        $user = (new User($this->mysqli))->getUserById($_SESSION['user_id']);

        $selectedCountryCode = $user['country_code'];
        $blocked = KycCheck::userIsBlocked($user);

        // Make fields in particulars form required.
        $required = true;

        include __DIR__ . "/../../../view/header.php";
        include __DIR__ . "/../../../view/kyc.php";
        include __DIR__ . "/../../../view/footer.php";
    }
}