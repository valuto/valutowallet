<?php

if (!function_exists('redirect')) {

    /**
     * Redirect to URL
     *
     * @param string $url
     * @return void
     */
    function redirect($url)
    {
        header("Location: /" . trim($url, '/'));
        exit;
    }
}

if (!function_exists('config')) {

    /**
     * Get config
     *
     * @param string $url
     * @return void
     */
    function config($group, $key)
    {
        $config = include __DIR__ . '/../config/' . $group . '.php';

        return $config[$key];
    }
}

if (!function_exists('lang')) {

    /**
     * Get config
     *
     * @param string $key
     * @return string
     */
    function lang($key)
    {
        return \Language\Lang::get($key);
    }
}

if (!function_exists('satoshitize')) {

    /**
     * Function by zelles to modify the number to bitcoin format ex. 0.00120000
     *
     * @param string $key
     * @return string
     */
    function satoshitize($satoshitize)
    {
        return sprintf("%.8f", $satoshitize);
    }
}

if (!function_exists('satoshitrim')) {

    /**
     * Function by zelles to trim trailing zeroes and decimal if need
     *
     * @param string $key
     * @return string
     */
    function satoshitrim($satoshitrim)
    {
        return rtrim(rtrim($satoshitrim, "0"), ".");
    }
}

if (!function_exists('response')) {

    function response($body, $status = 200)
    {
        return new \GuzzleHttp\Psr7\Response($status, [], $body);
    }

}

if (!function_exists('getallheaders')) {

    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('authed')) {

    function authed()
    {
        return isset($_SESSION['user_session']) && !empty($_SESSION['user_session']);
    }

}

if (!function_exists('dd')) {

    function dd($input)
    {
        var_dump($input);
        die;
    }

}

if (!function_exists('env')) {

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @source The Laravel framework
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (substr($value, 0, 1) === '"' && substr($value, strlen($value), -1) === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }

}

if (!function_exists('json')) {

    /**
     * Create simple JSON response string.
     * 
     * @param  string $type  e.g. 'success' or 'error'
     * @param  string $value 
     * @return string
     */
    function json($type, $value)
    {
        return json_encode([
            $type => $value,
        ]);
    }

}