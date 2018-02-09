<?php

namespace Controllers;

use Models\Client;

class Controller
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new Client(config('services', 'rpc')['host'], config('services', 'rpc')['port'], config('services', 'rpc')['username'], config('services', 'rpc')['password']);
    }

}