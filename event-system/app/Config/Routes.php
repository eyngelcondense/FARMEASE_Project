<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//landing page route
$routes->get('/', 'LandingController::index');


service('auth')->routes($routes);
