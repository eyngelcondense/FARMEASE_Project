<?php
use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */

// SSO — no auth filter, these are the entry points BEFORE login
$routes->get('staff/sso/authenticate', 'SsoController::authenticate');
$routes->get('staff/sso/callback',     'SsoController::callback');

// Logout route — accessible without auth filter
$routes->get('staff/logout', 'StaffController::logout');

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

$routes->get('staff/assignToBooking',         'StaffController::assignToBooking', ['filter' => 'authcheck']);
$routes->post('staff/assignToBooking',        'StaffController::assignToBooking', ['filter' => 'authcheck']);

$routes->get('staff-management',              'StaffManagementController::index', ['filter' => 'authcheck']);
$routes->get('staff-management/(:num)',       'StaffManagementController::show/$1',['filter' => 'authcheck']);

$routes->get('scheduling',                    'SchedulingController::index',      ['filter' => 'authcheck']);
$routes->get('scheduling/upcoming',           'SchedulingController::upcoming',   ['filter' => 'authcheck']);

// API Routes - for admin panel integration
$routes->group('api', ['filter' => 'cors'], function($routes) {
    // Staff API endpoints
    $routes->get('staff/list', 'ApiController::getStaffList');
    $routes->get('staff/(:num)', 'ApiController::getStaff/$1');
    $routes->post('staff', 'ApiController::createStaff');
    $routes->put('staff/(:num)', 'ApiController::updateStaff/$1');
    $routes->delete('staff/(:num)', 'ApiController::deleteStaff/$1');
    $routes->get('staff/stats', 'ApiController::getStaffStats');
    $routes->get('staff/(:num)/assignments', 'ApiController::getStaffAssignments/$1');
    
    // Assignments API endpoints
    $routes->get('assignments/list', 'ApiController::getAssignmentsList');
    $routes->get('assignments/(:num)', 'ApiController::getAssignment/$1');
    $routes->post('assignments', 'ApiController::createAssignment');
    $routes->put('assignments/(:num)', 'ApiController::updateAssignment/$1');
    $routes->delete('assignments/(:num)', 'ApiController::deleteAssignment/$1');
    $routes->get('assignments/stats', 'ApiController::getAssignmentStats');
    
    // Bookings API endpoints
    $routes->get('bookings/unassigned', 'ApiController::getUnassignedBookings');
    $routes->get('bookings/(:num)', 'ApiController::getBooking/$1');
});