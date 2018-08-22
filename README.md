# Valutowallet

Code used for valutowallet.com

Uses RPC to connect to valutod core on port 40332.

# Setup

1. Copy `.env.example` to `.env` and `.env.testing.example` to `.env.testing`. 
Edit the files to match your own server configuration.
2. `composer install`
3. Run the database migrations from the `migrations/` folder.
4. Make sure the `qrgen/phpqrcode/cache/` folder exists and is writable by the webserver.

# Testing

Feature tests can executed using phpunit.

The external _Laravel Dusk_ package developed by duncan3dc is required.

## Prerequisites

### Chromedriver

Start the _Chromedriver_ in your testing environment. E.g. 

`./vendor/laravel/dusk/bin/chromedriver-linux &`

### Setup valutowallet.testing

Create valutowallet.testing site in your webserver.

Add the environment variable 'ENVIRONMENT' with the value 'testing' to webserver site configuration. E.g. for nginx:

`fastcgi_param ENVIRONMENT 'testing';`

Restart your webserver.

The environment variable will tell the application to use the `.env.testing` configuration file instead of `.env`.

## Run PHPUnit

`./vendor/bin/phpunit --configuration phpunit.xml`
