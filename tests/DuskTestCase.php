<?php

namespace Tests;

use duncan3dc\Laravel\Dusk;
use Tests\TestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Models\User;
use Repositories\Database\UserRepository;
use Services\Tiers\TierLevel;

class DuskTestCase extends TestCase
{
    /**
     * Browser test driver instance.
     * 
     * @var duncan3dc\Laravel\Dusk
     */
    protected $browser;

    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->browser = new Dusk;
        $this->browser->setBaseUrl($this->baseUrl);
    }
    
    /**
     * Login as a user.
     * 
     * @param boolean $asKycVerified
     * @return void
     */
    protected function login($asKycVerified = true)
    {
        $user = new User($this->mysqli);
        $username = base64_encode(random_bytes(10));
        $password = base64_encode(random_bytes(10));
        $user->add($username, $password, $password);

        $user = (new User($this->mysqli))->getUserByUsername($username);

        if ($asKycVerified) {
            $this->kycVerifyUser($user['id']);
        }

        $this->browser->visit('')
            ->type('#loginUsername', $username)
            ->type('#loginPassword', $password)
            ->press('Log In');

        return [
            $user,
            $username,
            $password,
        ];
    }

    /**
     * KYC verify a new user, so the user can access the dashboard.
     * 
     * @param int $userId
     * @return void
     */
    protected function kycVerifyUser($userId)
    {
        $userRepository = new UserRepository($this->mysqli);

        $particulars = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'address_1' => 'Address 1',
            'address_2' => '',
            'zip_code' => '4200',
            'city' => 'Slagelse',
            'state' => '',
            'country' => 'DK',
            'email' => 'test@valuto.io',
            'phone_number' => '88888888',
        ];
        
        $result = $userRepository->updateProfile($userId, $particulars);

        // Refetch after update.
        $user = (new User($this->mysqli))->getUserById($userId);

        $userRepository->updateTier($userId, TierLevel::determine($user));
    }

}