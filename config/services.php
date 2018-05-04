<?php

return [

    'rpc' => [
        
        /**
         * Host
         */
        'host' => env('VALUTORPC_HOST', "127.0.0.1"),

        /**
         * Port
         */
        'port' => env('VALUTORPC_PORT', "40332"),

        /**
         * Username
         */
        'username' => env('VALUTORPC_USER', "valutorpc"),

        /**
         * Password
         */
        'password' => env('VALUTORPC_PASSWORD'),

    ],

];
