<?php

namespace Tests;

use duncan3dc\Laravel\Dusk;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Mysqli;
use Models\User;

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

        $this->browser->setBaseUrl('http://valutowallet.testing');

        $this->loadTestingEnvironmentFile();
        $this->loadDatabase();
        $this->feedServerVariables();
    }

    /**
     * Override dotenv file with testing configuration.
     * 
     * @return void
     */
    protected function loadTestingEnvironmentFile()
    {
        global $dotenv;
        $dotenv = new Dotenv(__DIR__ . '/../', '.env.testing');
        $dotenv->load();
    }

    /**
     * Instantiate MySQL connection.
     * 
     * @return void
     */
    protected function loadDatabase()
    {
        $this->mysqli = new Mysqli(
            config('database', 'host'), 
            config('database', 'username'), 
            config('database', 'password'), 
            config('database', 'database')
        );
    }

    /**
     * Feed $_SERVER global with data.
     * 
     * @return void
     */
    protected function feedServerVariables()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }
    
    /**
     * Login as a user.
     * 
     * @return void
     */
    protected function login()
    {
        $user = new User($this->mysqli);
        $username = base64_encode(random_bytes(10));
        $password = base64_encode(random_bytes(10));
        $user->add($username, $password, $password);

        $this->browser->visit('')
            ->type('#loginUsername', $username)
            ->type('#loginPassword', $password)
            ->press('Log In');
    }

}