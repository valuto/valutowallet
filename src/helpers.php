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
