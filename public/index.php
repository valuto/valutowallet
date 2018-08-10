<?php

session_start();
header('Cache-control: private'); // IE 6 FIX

require __DIR__ . '/../vendor/autoload.php';

/**
 * Bootstrap application.
 */
require __DIR__ . '/../src/bootstrap.php';

/**
 * Load routes.
 */
require __DIR__ . '/../src/routes.php';

/**
 * Resolve requested route.
 */
(new \Router\Route)->resolve();