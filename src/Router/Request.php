<?php

namespace Router;

class Request
{

    /**
     * Get URI path
     * 
     * @param bool $trim Trim slashes. Default true
     * @return string
     */
    public function path($trim = true)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return $trim ? trim($uri, '/') : $uri ;
    }

    /**
     * Get current HTTP method
     * 
     * @return string
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * If HTTP method is PUT, parse body parameters
     * to $_POST.
     */
    public function parseParameters()
    {
        if ($this->method() === 'PUT') {
            parse_str(file_get_contents('php://input'), $_POST);
        }
    }
}