<?php

/**
 * Map middleware names with classes here
 */

return [

    'auth' => \Middlewares\Authenticate::class,
    'admin' => \Middlewares\Administrator::class,

];