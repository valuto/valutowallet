<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Dotenv\Dotenv;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Mysqli;
use Models\User;
use Repositories\Database\UserRepository;
use Services\Tiers\TierLevel;

class TestCase extends PHPUnitTestCase
{
    /**
     * Wallet testing base URL.
     * 
     * @var string
     */
    protected $baseUrl;

    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = 'http://valutowallet.testing';

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
    
}