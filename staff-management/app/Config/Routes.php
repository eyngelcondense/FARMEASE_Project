<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->group('staff', function($routes) {
    $routes->get('dashboard', 'StaffController::dashboard');
    $routes->get('profile', 'StaffController::profile');
    $routes->get('schedule', 'StaffController::schedule');
});
$routes->post('staff/login', 'AuthController::login');
