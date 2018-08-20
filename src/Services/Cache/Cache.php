<?php

namespace Services\Cache;

class Cache
{
    private static $handler = NULL;

    public static function __callStatic($name, $args)
    {
        $callback = array(self::getHandler(), $name);

        return call_user_func_array($callback, $args);
    }

    private static function getHandler()
    {
        if (self::$handler) {
            return self::$handler;
        }
    
        switch (config('cache', 'driver')) {
            case 'redis':
                self::$handler = new Predis\Client([
                    'scheme' => 'tcp',
                    'host'   => config('cache', 'host'),
                    'port'   => config('cache', 'port'),
                ]);
                break;
        }

        return self::$handler;
    }
}