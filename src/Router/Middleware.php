<?php

namespace Router;

class Middleware
{

    /**
     * Check if middleware resolves positive
     * 
     * @param array $middlewares
     * @return bool
     */
    public function check($middlewares)
    {
        if (empty($middlewares)) {
            return true;
        }

        foreach ($middlewares as $middleware) {
            $class = config('middleware', $middleware);

            if (!isset($class) || (new $class)->handle() !== true) {
                return false;
            }
        }

        return true;
    }

}