<?php

namespace Language;

class Lang
{

    /**
     * Translations loaded to memory
     */
    protected static $translations;

    /**
     * Get translation string by key
     * 
     * @return string
     */
    public static function get($key)
    {
        $translations = self::loadTranslations();

        return isset($translations[$key]) ? $translations[$key] : $key;
    }

    /**
     * Set language
     * 
     * @return void
     */
    public static function setLanguage($language)
    {
        $_SESSION['lang'] = $language;

        setcookie('lang', $language, time() + (3600 * 24 * 30));
    }

    /**
     * Get current language
     * 
     * @return string 
     */
    public static function getLanguage()
    {
        return ($_SESSION['lang'] ?? ( $_COOKIE['lang'] ?? config('app', 'default_lang') ));
    }

    /**
     * Load translations from memory or file
     * 
     * @return array Translations
     */
    protected static function loadTranslations()
    {
        $lang = self::getLanguage();
        $file = __DIR__ . '/../../languages/' . $lang . '.php';

        if (!isset(self::$translations[$lang]) && file_exists($file)) {
            self::$translations[$lang] = include $file;
        }
    
        return self::$translations[$lang];
    }
}