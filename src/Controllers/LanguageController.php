<?php

namespace Controllers;

use Language\Lang;

class LanguageController extends Controller
{

    /**
     * Set language
     */
    public function update()
    {
        $language = $_POST['language'];

        Lang::setLanguage($language);
    }

}