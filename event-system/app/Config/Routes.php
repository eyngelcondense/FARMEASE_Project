<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//landing page route
$routes->get('/', 'LandingController::index');



$routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->get('login', 'LoginController::loginView');
    $routes->post('login', 'LoginController::loginAction');
    $routes->get('register', 'RegisterController::registerView');
    $routes->post('register', 'RegisterController::registerAction');
    $routes->get('logout', 'LoginController::logout');
    
});

$routes->group('client', ['filter' => 'group:client'], static function ($routes) {
    $routes->get('home', 'Client\HomeController::index');
});
$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});

$routes->get('home', 'HomeController::index', ['filter' => 'authredirect']);
$routes->get('landing', 'LandingController::index');