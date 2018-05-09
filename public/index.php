<?php

session_start();
header('Cache-control: private'); // IE 6 FIX

require __DIR__ . '/../vendor/autoload.php';

/**
 * Load .env file.
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

/**
 * Sentry error reporting.
 */
$sentryClient = new Raven_Client(config('sentry', 'auth_url'));
$error_handler = new Raven_ErrorHandler($sentryClient);
$error_handler->registerExceptionHandler();
$error_handler->registerErrorHandler();
$error_handler->registerShutdownFunction();

/**
 * Load routes.
 */
require __DIR__ . '/../routes.php';

/**
 * Instantiate MySQL connection.
 */
$mysqli = new Mysqli(config('database', 'host'), config('database', 'username'), config('database', 'password'), config('database', 'database'));

/**
 * Resolve requested route.
 */
(new \Router\Route)->resolve();