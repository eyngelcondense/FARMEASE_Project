<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//landing page route
$routes->get('/', 'LandingController::index', ['filter' => 'redirectIfAuthenticated']);

$routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->get('login', 'LoginController::loginView', ['filter' => 'redirectIfAuthenticated']);
    $routes->post('login', 'LoginController::loginAction');
    $routes->get('register', 'RegisterController::registerView', ['filter' => 'redirectIfAuthenticated']);
    $routes->post('register', 'RegisterController::registerAction');
    $routes->get('logout', 'LoginController::logout');
    
});

$routes->group('', ['namespace' => 'App\Controllers', 'filter' => 'group:client'], static function ($routes) {
    $routes->get('home', 'HomeController::index');
    $routes->get('booking', 'BookingController::index');
});

$routes->group('', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});

$routes->get('landing', 'LandingController::index');