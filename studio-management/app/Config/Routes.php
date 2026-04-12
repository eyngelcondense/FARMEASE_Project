<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');

// Test route for debugging
$routes->get('test', 'TestController::index');

// SSO routes — no auth filter required
$routes->get('studio/sso/authenticate', 'SsoController::authenticate');

// Logout route — accessible without auth filter
$routes->get('studio/logout', 'StudioController::logout');

$routes->group('studio', ['filter' => 'authcheck'], function($routes) {
    $routes->get('dashboard', 'StudioController::dashboard');
    $routes->get('bookings', 'StudioController::bookings');
    $routes->get('info', 'StudioController::info');
    $routes->get('gallery', 'StudioController::gallery');
    $routes->get('schedule', 'StudioController::schedule');
});
$routes->get('availability', 'AvailabilityController::index', ['filter' => 'authcheck']);
$routes->get('assignment', 'AssignmentController::index', ['filter' => 'authcheck']);
$routes->get('staff-management', 'StaffManagementController::index', ['filter' => 'authcheck']);
$routes->get('scheduling', 'SchedulingController::index', ['filter' => 'authcheck']);


// API Routes - for admin panel integration
$routes->group('api', ['filter' => 'cors'], function($routes) {
    // Studio API endpoints
    $routes->get('studio/list', 'ApiController::getStudioList');
    $routes->get('studio/(:num)', 'ApiController::getStudio/$1');
    $routes->post('studio', 'ApiController::createStudio');
    $routes->put('studio/(:num)', 'ApiController::updateStudio/$1');
    $routes->delete('studio/(:num)', 'ApiController::deleteStudio/$1');
    $routes->get('studio/available/(:any)/(:num)', 'ApiController::getAvailableStudios/$1/$2');
    $routes->get('studio/(:num)/availability/(:any)', 'ApiController::getStudioAvailability/$1/$2');
    $routes->get('studio/(:num)/calendar/(:num)/(:num)', 'ApiController::getStudioCalendar/$1/$2/$3');
    $routes->get('studio/(:num)/pricing/(:num)', 'ApiController::getStudioPricing/$1/$2');
    
    // Studio Booking API endpoints
    $routes->post('booking/create', 'ApiController::createStudioBooking');
    $routes->delete('booking/(:num)', 'ApiController::deleteStudioBooking/$1');
    $routes->get('booking/studio/(:num)', 'ApiController::getStudioBookings/$1');
    
    // Stats endpoint
    $routes->get('stats', 'ApiController::getStudioStats');
});