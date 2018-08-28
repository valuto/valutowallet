<?php

/**
 * Load .env file.
 */
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../', env('ENVIRONMENT') === 'testing' ? '.env.testing' : '.env');
$dotenv->load();

/**
 * Sentry error reporting.
 */
$sentryClient = new Raven_Client(config('sentry', 'auth_url'));
$errorHandler = new Raven_ErrorHandler($sentryClient);
$errorHandler->registerExceptionHandler();
$errorHandler->registerErrorHandler();
$errorHandler->registerShutdownFunction();

/**
 * Configure session storage.
 */
$sessionSaveHandler = config('app', 'session_save_handler');
$sessionSavePath    = config('app', 'session_save_path');
if ($sessionSaveHandler) {
    ini_set('session.save_handler', $sessionSaveHandler);
    ini_set('session.save_path', $sessionSavePath);
}

/**
 * Start session.
 */
session_start();

/**
 * Do not cache any content. It's intended for single user use.
 */
header('Cache-control: private');

/**
 * Instantiate MySQL connection.
 */
$mysqli = new Mysqli(config('database', 'host'), config('database', 'username'), config('database', 'password'), config('database', 'database'));