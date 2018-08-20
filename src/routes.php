<?php

$route = new \Router\Route();

/**
 * Administration.
 */
$route->get('admin', 'Controllers\AdminController@index')->middleware(['auth', 'admin']);
$route->delete('admin', 'Controllers\AdminController@destroy')->middleware(['auth', 'admin']);
$route->post('admin', 'Controllers\AdminController@store')->middleware(['auth', 'admin']);
$route->put('admin/unlock', 'Controllers\AdminController@unlock')->middleware(['auth', 'admin']);
$route->put('admin/lock', 'Controllers\AdminController@lock')->middleware(['auth', 'admin']);
$route->delete('admin/delete-user', 'Controllers\AdminController@deleteUser')->middleware(['auth', 'admin']);
$route->get('admin/info', 'Controllers\AdminController@info')->middleware(['auth', 'admin']);
$route->post('admin/info', 'Controllers\AdminController@info')->middleware(['auth', 'admin', 'csrf']); // Make post route accesible until refactoring of that controller method is done
$route->post('admin/bounty/signup/payout', 'Controllers\AdminBountyController@store')->middleware(['auth', 'admin', 'csrf']);

/**
 * Wallet.
 */
$route->if(authed())->get('', 'Controllers\DashboardController@index')->middleware('auth');
$route->post('accept-disclaimer', 'Controllers\DashboardController@acceptDisclaimer')->middleware('auth');
$route->put('lang', 'Controllers\LanguageController@update');

$route->put('auth/password', 'Controllers\Auth\PasswordController@update')->middleware('auth');
$route->post('auth/twofactorauth', 'Controllers\Auth\TwoFactorAuthController@store')->middleware('auth');
$route->put('auth/twofactorauth', 'Controllers\Auth\TwoFactorAuthController@update')->middleware('auth');
$route->delete('auth/twofactorauth', 'Controllers\Auth\TwoFactorAuthController@destroy')->middleware('auth');

$route->put('user/profile', 'Controllers\UserProfileController@update')->middleware('auth');

$route->post('wallet/newaddress', 'Controllers\WalletController@newaddress')->middleware('auth');
$route->post('wallet/withdraw', 'Controllers\WalletController@withdraw')->middleware('auth');

$route->get('qrcode', 'Controllers\QrcodeController@show')->middleware('auth');

$route->post('auth/login', 'Controllers\Auth\LoginController@store')->middleware('recaptcha');
$route->post('auth/logout', 'Controllers\Auth\LoginController@destroy');
$route->post('auth/register', 'Controllers\Auth\RegisterController@store')->middleware(['recaptcha', 'countryAllowed']);

/**
 * Frontpage.
 */
$route->get('', 'Controllers\HomeController@index');
$route->get('index.php', 'Controllers\HomeController@redirect');
$route->get('/country-blocked', 'Controllers\Auth\CountryBlockedController@show');

/**
 * Know Your Customer
 */
$route->get('kyc/status', 'Controllers\Kyc\StatusController@show');
$route->post('kyc/skip', 'Controllers\Kyc\SkipController@store');
$route->get('kyc', 'Controllers\Kyc\FormController@show');

/**
 * API version 1.
 */
$route->post('api/v1/access-token', 'Controllers\Api\V1\Auth\AccessTokenController@store');
$route->get('api/v1/user', 'Controllers\Api\V1\UserCheckController@show')->middleware('apiauth');
$route->post('api/v1/user', 'Controllers\Api\V1\UserCreateController@store')->middleware('apiauth');
$route->get('api/v1/user/activate', 'Controllers\Api\V1\UserActivateController@show');
$route->post('api/v1/user/activate', 'Controllers\Api\V1\UserActivateController@store')->middleware('recaptcha');