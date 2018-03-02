<?php

$route = new \Router\Route();

$route->get('admin', 'Controllers\AdminController@index')->middleware(['auth', 'admin']);
$route->delete('admin', 'Controllers\AdminController@destroy')->middleware(['auth', 'admin']);
$route->post('admin', 'Controllers\AdminController@store')->middleware(['auth', 'admin']);
$route->put('admin/unlock', 'Controllers\AdminController@unlock')->middleware(['auth', 'admin']);
$route->put('admin/lock', 'Controllers\AdminController@lock')->middleware(['auth', 'admin']);
$route->delete('admin/delete-user', 'Controllers\AdminController@deleteUser')->middleware(['auth', 'admin']);
$route->get('admin/info', 'Controllers\AdminController@info')->middleware(['auth', 'admin']);
$route->post('admin/info', 'Controllers\AdminController@info')->middleware(['auth', 'admin']); // Make post route accesible until refactoring of that controller method is done

$route->get('', 'Controllers\DashboardController@index')->middleware('auth');
$route->post('accept-disclaimer', 'Controllers\DashboardController@acceptDisclaimer')->middleware('auth');
$route->put('lang', 'Controllers\LanguageController@update');

$route->put('auth/password', 'Controllers\Auth\PasswordController@update')->middleware('auth');
$route->post('auth/twofactorauth', 'Controllers\Auth\TwoFactorAuthController@store')->middleware('auth');
$route->put('auth/twofactorauth', 'Controllers\Auth\TwoFactorAuthController@update')->middleware('auth');
$route->delete('auth/twofactorauth', 'Controllers\Auth\TwoFactorAuthController@destroy')->middleware('auth');

$route->post('wallet/newaddress', 'Controllers\WalletController@newaddress')->middleware('auth');
$route->post('wallet/withdraw', 'Controllers\WalletController@withdraw')->middleware('auth');

$route->get('qrcode', 'Controllers\QrcodeController@show')->middleware('auth');

$route->post('auth/login', 'Controllers\Auth\LoginController@store')->middleware('recaptcha');
$route->post('auth/logout', 'Controllers\Auth\LoginController@destroy');
$route->post('auth/register', 'Controllers\Auth\RegisterController@store')->middleware('recaptcha');
$route->get('', 'Controllers\HomeController@index');
$route->get('index.php', 'Controllers\HomeController@redirect');