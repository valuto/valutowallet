<?php

namespace Factories;

use Models\Client;

class ClientFactory
{

    /**
     * Construct requested class.
     *
     * @return Models\Client
     */
    public static function build()
    {
        return new Client(config('services', 'rpc')['host'], config('services', 'rpc')['port'], config('services', 'rpc')['username'], config('services', 'rpc')['password']);
    }

}