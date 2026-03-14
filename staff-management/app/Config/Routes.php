<?php
use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */

// SSO — no auth filter, these are the entry points BEFORE login
$routes->get('staff/sso/authenticate', 'SsoController::authenticate');
$routes->get('staff/sso/callback',     'SsoController::callback');

// Staff portal — auth required
$routes->group('staff', ['filter' => 'authcheck'], function($routes) {
    $routes->get('dashboard',      'StaffController::dashboard');
    $routes->get('profile',        'StaffController::profile');
    $routes->get('assignments',    'StaffController::assignments');
    $routes->get('schedule',       'StaffController::schedule');
    $routes->post('profile/update','StaffController::updateProfile');
});

$routes->get('availability',                  'AvailabilityController::index',    ['filter' => 'authcheck']);
$routes->get('availability/create',           'AvailabilityController::create',   ['filter' => 'authcheck']);
$routes->post('availability/store',           'AvailabilityController::store',    ['filter' => 'authcheck']);
$routes->get('availability/edit/(:num)',      'AvailabilityController::edit/$1',  ['filter' => 'authcheck']);
$routes->post('availability/update/(:num)',   'AvailabilityController::update/$1',['filter' => 'authcheck']);
$routes->post('availability/delete/(:num)',   'AvailabilityController::delete/$1',['filter' => 'authcheck']);
$routes->get('availability/calendar',         'AvailabilityController::calendar', ['filter' => 'authcheck']);

$routes->get('assignment',                    'AssignmentController::index',      ['filter' => 'authcheck']);
$routes->post('assignment/accept/(:num)',     'AssignmentController::accept/$1',  ['filter' => 'authcheck']);
$routes->post('assignment/complete/(:num)',   'AssignmentController::complete/$1',['filter' => 'authcheck']);

$routes->get('staff-management',              'StaffManagementController::index', ['filter' => 'authcheck']);
$routes->get('staff-management/(:num)',       'StaffManagementController::show/$1',['filter' => 'authcheck']);

$routes->get('scheduling',                    'SchedulingController::index',      ['filter' => 'authcheck']);
$routes->get('scheduling/upcoming',           'SchedulingController::upcoming',   ['filter' => 'authcheck']);