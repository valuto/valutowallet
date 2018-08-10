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
 * Instantiate MySQL connection.
 */
$mysqli = new Mysqli(config('database', 'host'), config('database', 'username'), config('database', 'password'), config('database', 'database'));