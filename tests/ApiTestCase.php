<?php

namespace Tests;

use Tests\TestCase;
use Models\User;

class ApiTestCase extends TestCase
{
    /**
     * HTTP client.
     * 
     * @var GuzzleHttp\Client
     */
    protected $http;

    /**
     * API clients.
     * 
     * @var array
     */
    protected $clients;

    /**
     * Test user.
     * 
     * @var stdClass
     */
    protected $testUser;

    /**
     * Constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->http = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl,
        ]);
        
        $this->clients = config('api', 'clients');
    }

    /**
     * Create test user.
     * 
     * @return void
     */
    public function createTestUser()
    {
        $this->testUser = new \stdClass();
        $this->testUser->username = base64_encode(random_bytes(10));
        $this->testUser->password = base64_encode(random_bytes(10));

        $user = new User($this->mysqli);
        $user->add($this->testUser->username, $this->testUser->password, $this->testUser->password);
    }

    /**
     * Default API headers.
     * 
     * @return array
     */
    public function defaultHeaders()
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
    }
}