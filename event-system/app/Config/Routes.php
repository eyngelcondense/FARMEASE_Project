<?php

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Shield\Authentication\Authentication;
use CodeIgniter\Shield\Controllers\MagicLinkController;

/**
 * @var RouteCollection $routes
 */

//landing page route
$routes->get('/', 'ClientController::landing', ['filter' => 'redirectIfAuthenticated']);

$routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->get('login', 'LoginController::loginView', ['filter' => 'redirectIfAuthenticated']);
    $routes->post('login', 'LoginController::loginAction');
    $routes->get('register', 'RegisterController::registerView', ['filter' => 'redirectIfAuthenticated']);
    $routes->post('register', 'RegisterController::registerAction');
    $routes->get('register-success', 'RegisterController::registerSuccess');
    $routes->get('activate-account', 'RegisterController::activateAccount');
    $routes->get('logout', 'LoginController::logout');
    $routes->get('forgot-password', 'ForgotPasswordController::forgotPasswordView', ['filter' => 'redirectIfAuthenticated'], ['as' => 'forgot_password']);
    $routes->post('forgot-password', 'ForgotPasswordController::sendResetLink');
    $routes->get('forgot-password-message', 'ForgotPasswordController::forgotPasswordMessage', ['filter' => 'redirectIfAuthenticated'], ['as' => 'forgot_password_message']);
    $routes->get('set-password', 'ResetPasswordController::setPasswordForm', ['as' => 'set_password']);
    $routes->post('set-password', 'ResetPasswordController::setPasswordAction');
    $routes->get('auth-link/login', 'MagicLinkController::login');
    $routes->get('auth-link/show', 'CodeIgniter\Shield\Controllers\MagicLinkController::show');
    $routes->get('reset-password', 'ForgotPasswordController::resetPasswordView');
    $routes->post('reset-password', 'ForgotPasswordController::handleResetPassword');

});
service('auth')->routes($routes);

$routes->group('', ['namespace' => 'App\Controllers', 'filter' => 'group:client'], static function ($routes) {
    $routes->get('home', 'ClientController::home');
    $routes->get('booking', 'ClientController::booking');
    $routes->get('packages', 'ClientController::packages');
    $routes->get('gallery', 'ClientController::gallery');
    $routes->get('testimonials', 'ClientController::testimonials');
    $routes->get('contact', 'ClientController::contact');
});

$routes->group('', ['namespace' => 'App\Controllers', 'filter' => 'group:admin'], static function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboardView');
    $routes->get('admin-bookings', 'AdminController::bookingsView');
    $routes->get('admin-payments', 'AdminController::paymentsView');
    $routes->get('venue-packages', 'AdminController::venueView');
});

$routes->get('landing', 'ClientController::landing', ['filter' => 'redirectIfAuthenticated']);