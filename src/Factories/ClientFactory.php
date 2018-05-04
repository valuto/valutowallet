<?php

namespace Factories;

use Services\ValutoDaemon\Client;

class ClientFactory
{

    /**
     * Construct requested class.
     *
     * @return Services\ValutoDaemon\Client
     */
    public static function build()
    {
        return new Client(config('services', 'rpc')['host'], config('services', 'rpc')['port'], config('services', 'rpc')['username'], config('services', 'rpc')['password']);
    }

}