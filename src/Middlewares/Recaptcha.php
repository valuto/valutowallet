<?php

namespace Middlewares;

use Models\HttpClient;

class Recaptcha
{

    public function handle()
    {
        $result = json_decode(HttpClient::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('captcha', 'secret_key'),
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ]));

        return isset($result->success) && $result->success === true;
    }
    
}