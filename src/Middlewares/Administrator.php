<?php

namespace Middlewares;

class Administrator
{

    public function handle()
    {
        if (!empty($_SESSION['user_admin']) && $_SESSION['user_admin'] == 1) {

            return true;

        }

        return false;
    }
    
}