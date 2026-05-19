<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');

// Test route for debugging
$routes->get('test', 'TestController::index');

// Studio management routes
$routes->group('studio', ['filter' => 'authcheck'], function($routes) {
    $routes->get('', 'StudioController::index');
    $routes->get('dashboard', 'StudioController::dashboard');
    $routes->get('bookings', 'StudioController::bookings');
    $routes->get('info', 'StudioController::info');
    $routes->get('profile', 'StudioController::info');
    $routes->post('update-info', 'StudioController::updateInfo');
    $routes->get('gallery', 'StudioController::gallery');
    $routes->get('schedule', 'StudioController::schedule');
    $routes->get('available', 'StudioController::available');
    $routes->get('assignments', 'StudioController::assignments');
    $routes->get('feedback', 'StudioController::feedback');
    $routes->get('create', 'StudioController::create');
    $routes->post('store', 'StudioController::store');
    $routes->get('show/(:num)', 'StudioController::show/$1');
    $routes->get('edit/(:num)', 'StudioController::edit/$1');
    $routes->post('update/(:num)', 'StudioController::update/$1');
    $routes->get('delete/(:num)', 'StudioController::delete/$1');
    $routes->post('upload-images', 'StudioController::uploadImages');
    $routes->post('update-image', 'StudioController::updateImage');
    $routes->post('set-primary', 'StudioController::setPrimaryImage');
    $routes->post('delete-image', 'StudioController::deleteImage');
});

// SSO routes — no auth filter required
$routes->get('studio/sso/authenticate', 'SsoController::authenticate');

// Logout route — accessible without auth filter
$routes->get('studio/logout', 'StudioController::logout');
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