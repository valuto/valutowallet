<?php

namespace Middlewares;

class Authenticate
{

    public function handle()
    {
        if (!empty($_SESSION['user_session'])) {
            if (empty($_SESSION['token'])) {
                $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));
            }

            return true;
        }

        return false;
    }
    
}