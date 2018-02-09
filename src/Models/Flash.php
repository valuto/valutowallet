<?php

namespace Models;

class Flash {

    public static function save($key, $value)
    {
        $data = isset($_SESSION['flash_data']) ? $_SESSION['flash_data'] : array();

        $data[$key] = $value;

        $_SESSION['flash_data'] = $data;
    }

    public static function has($key)
    {
        return isset($_SESSION['flash_data']) && isset($_SESSION['flash_data'][$key]);
    }

    public static function show($key)
    {
        if (self::has($key)) {
            return $_SESSION['flash_data'][$key];
        } else {
            return '';
        }
    }

    public static function showOnce($key)
    {
        $value = self::show($key);

        self::delete($key);

        return $value;
    }

    public static function delete($key)
    {
        unset($_SESSION['flash_data'][$key]);

        return true;
    }

    public static function clear()
    {
        unset($_SESSION['flash_data']);
    }

}