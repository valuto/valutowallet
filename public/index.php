<?php

session_start();
header('Cache-control: private'); // IE 6 FIX

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../routes.php';

$mysqli = new Mysqli(config('database', 'host'), config('database', 'username'), config('database', 'password'), config('database', 'database'));

(new \Router\Route)->resolve();