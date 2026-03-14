<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->group('studio', ['filter' => 'authcheck'], function($routes) {
    $routes->get('dashboard', 'StudioController::dashboard');
});
$routes->get('availability', 'AvailabilityController::index', ['filter' => 'authcheck']);
$routes->get('assignment', 'AssignmentController::index', ['filter' => 'authcheck']);
$routes->get('staff-management', 'StaffManagementController::index', ['filter' => 'authcheck']);
$routes->get('scheduling', 'SchedulingController::index', ['filter' => 'authcheck']);


$routes->get('studio/sso/authenticate', 'SsoController::authenticate');