<?php

namespace Controllers;

use Factories\ClientFactory;

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
        $this->client = ClientFactory::build();
    }

}