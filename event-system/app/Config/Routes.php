<?php

use CodeIgniter\Router\RouteCollection;

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
    $routes->get('logout', 'LoginController::logout');
    
});

$routes->group('', ['namespace' => 'App\Controllers', 'filter' => 'group:client'], static function ($routes) {
    $routes->get('home', 'ClientController::home');
    $routes->get('booking', 'ClientController::booking');
    $routes->get('packages', 'ClientController::packages');
    $routes->get('gallery', 'ClientController::gallery');
    $routes->get('testimonials', 'ClientController::testimonials');
    $routes->get('contact', 'ClientController::contact');
});

$routes->group('', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
});

$routes->get('landing', 'ClientController::landing');