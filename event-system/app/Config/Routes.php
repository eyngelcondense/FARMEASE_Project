<?php

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Shield\Authentication\Authentication;
use CodeIgniter\Shield\Controllers\MagicLinkController;

/**
 * @var RouteCollection $routes
 */

//landing page route 
$routes->get('/', 'ClientController::landing', ['filter' => 'redirectIfAuthenticated']);
$routes->get('landing', 'ClientController::landing', ['filter' => 'redirectIfAuthenticated']);
$routes->get('request-data', 'DataRequestController::index');
$routes->post('data-request/submit', 'DataRequestController::submitRequest');

//auth routes
$routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
    $routes->get('login', 'LoginController::loginView', ['filter' => 'redirectIfAuthenticated']);
    $routes->post('login', 'LoginController::loginAction');

    $routes->get('register', 'RegisterController::registerView', ['filter' => 'redirectIfAuthenticated']);
    $routes->post('register', 'RegisterController::registerAction');
    $routes->get('register-success', 'RegisterController::registerSuccess');
    $routes->get('activate-account', 'RegisterController::activateAccount');

    $routes->get('logout', 'LoginController::logout');

    $routes->get('forgot-password', 'ForgotPasswordController::forgotPasswordView', ['filter' => 'redirectIfAuthenticated'], ['as' => 'forgot_password']);
    $routes->post('forgot-password', 'ForgotPasswordController::sendResetLink');
    $routes->get('forgot-password-message', 'ForgotPasswordController::forgotPasswordMessage', ['filter' => 'redirectIfAuthenticated'], ['as' => 'forgot_password_message']);
    $routes->get('set-password', 'ResetPasswordController::setPasswordForm', ['as' => 'set_password']);
    $routes->post('set-password', 'ResetPasswordController::setPasswordAction');

    $routes->get('auth-link/login', 'MagicLinkController::login');
    $routes->get('auth-link/show', 'CodeIgniter\Shield\Controllers\MagicLinkController::show');

    $routes->get('reset-password', 'ForgotPasswordController::resetPasswordView');
    $routes->post('reset-password', 'ForgotPasswordController::handleResetPassword');

});
// service('auth')->routes($routes);

//client routes
$routes->group('', ['namespace' => 'App\Controllers', 'filter' => 'group:client'], static function ($routes) {
    $routes->get('home', 'ClientController::home');

    $routes->get('bookings', 'BookingController::index');
    $routes->post('booking/submit', 'BookingController::submit');
    $routes->get('booking/booked-dates', 'BookingController::bookedDates');
    $routes->get('booking/available-time-slots', 'BookingController::availableTimeSlots');
    $routes->get('booking/package-venues', 'BookingController::packageVenues');

    $routes->get('booking_history', 'BookingController::history');
    $routes->get('booking/details/(:num)', 'BookingController::bookingDetails/$1');
    $routes->get('booking/get-addons', 'BookingController::getAddons');

    $routes->post('booking/submit', 'BookingController::submitBooking');
    $routes->get('booking/booked-dates', 'BookingController::getBookedDates');
    $routes->get('booking/get-addons', 'BookingController::getAddons');

    $routes->post('payments/process/(:num)', 'PaymentsController::process/$1');
    $routes->post('payments/create-redirect', 'PaymentsController::createRedirect');
    $routes->get('payments/success', 'PaymentsController::success');
    $routes->post('payments/manual/(:num)', 'PaymentsController::manual/$1');
    $routes->get('payments/modal/(:num)', 'PaymentsController::modal/$1');
    $routes->post('payments/submit', 'PaymentsController::submit');
    $routes->get('payments/failed', 'PaymentsController::failed');
    $routes->get('receipts/(:any)', 'PaymentsController::showReceipt/$1');

    $routes->get('packages', 'ClientController::packages');
    $routes->get('gallery', 'ClientController::gallery');
    $routes->get('api/gallery/getVenueImages', 'AdminGalleryController::getVenueImages');
    $routes->get('api/packages/getPackagesWithVenues', 'PackageController::getPackagesWithVenues');
    $routes->get('testimonials', 'FeedbackController::testimonials');
    $routes->post('feedback/submit', 'FeedbackController::submitFeedback');
    $routes->get('profile', 'ClientController::profileView');
    $routes->post('profile-update', 'ClientController::profileUpdate');

    $routes->get('payments/debug-keys', 'PaymentsController::debugKeys');
    $routes->get('payments/test-paymongo', 'PaymentsController::testPayMongoDirect');
    $routes->get('payments/db-test/(:num)', 'PaymentsController::dbTest/$1');

    $routes->get('client/contracts', 'ContractsController::index');
    $routes->get('client/contracts/view/(:num)', 'ContractsController::view/$1');
    $routes->post('client/contracts/sign/(:num)', 'ContractsController::sign/$1');
    $routes->get('client/contracts/download/(:num)', 'ContractsController::download/$1');
    $routes->post('client/contracts/agree/(:num)', 'ContractsController::agree/$1');
    $routes->get('client/contracts/debugContractAccess/(:num)', 'ContractsController::debugContractAccess/$1');
});

//admin routes
$routes->group('', ['namespace' => 'App\Controllers', 'filter' => 'group:admin'], static function ($routes) {
    
    $routes->get('admin/dashboard', 'DashboardController::index');
    $routes->get('admin/dashboard/stats', 'DashboardController::getStats');
    $routes->get('admin/dashboard/chart-data', 'DashboardController::getChartData');
    $routes->get('admin/dashboard/recent-bookings', 'DashboardController::getRecentBookings');
    $routes->get('admin/dashboard/upcoming-events', 'DashboardController::getUpcomingEvents');

    $routes->get('admin/bookings', 'AdminBookingsController::index');
    $routes->get('bookings/data', 'AdminBookingsController::getBookingsAjax');
    $routes->get('bookings/(:num)/details', 'AdminBookingsController::getBookingDetails/$1');
    $routes->post('bookings/(:num)/approve', 'AdminBookingsController::approveBooking/$1');
    $routes->post('bookings/(:num)/approve-with-conflicts', 'AdminBookingsController::approveBookingWithConflicts/$1');
    $routes->post('bookings/(:num)/reject', 'AdminBookingsController::rejectBooking/$1');
    $routes->get('bookings/stats', 'AdminBookingsController::getBookingStats');

    $routes->get('admin/payments', 'AdminPaymentsController::index');
    $routes->get('admin/payments/(:num)', 'AdminPaymentsController::show/$1');
    $routes->post('admin/payments/verify/(:num)', 'AdminPaymentsController::verify/$1');
    $routes->post('admin/payments/reject/(:num)', 'AdminPaymentsController::reject/$1');

    $routes->get('feedback', 'AdminFeedbacksController::feedbackView');
    $routes->post('feedback/reject/(:num)', 'AdminFeedbacksController::reject/$1');
    $routes->post('feedback/delete/(:num)', 'AdminFeedbacksController::delete/$1');
    $routes->post('feedback/approve/(:num)', 'AdminFeedbacksController::approve/$1');

    $routes->get('admin/gallery', 'AdminGalleryController::index');
    $routes->post('admin/gallery/upload', 'AdminGalleryController::upload');
    $routes->post('admin/gallery/toggle/(:num)', 'AdminGalleryController::toggle/$1');
    $routes->delete('admin/gallery/delete/(:num)', 'AdminGalleryController::delete/$1');
    $routes->get('admin/gallery/items', 'AdminGalleryController::items');
    
    $routes->get('admin/calendar', 'CalendarController::index');
    $routes->get('admin/calendar/data', 'CalendarController::getCalendarData');
    $routes->get('admin/calendar/time-slots', 'CalendarController::getTimeSlots');
    $routes->get('admin/calendar/booking/(:num)', 'CalendarController::getBookingDetails/$1');
    $routes->get('admin/calendar/grid-data', 'CalendarController::getCalendarGridData');
    $routes->post('admin/calendar/update-status', 'CalendarController::updateBookingStatus');   

    $routes->get('manage-staff', 'AdminController::manageStaffView');

    $routes->get('notifications', 'NotificationsController::index');
    $routes->get('notifications/get', 'NotificationsController::get');
    $routes->post('notifications/mark-read/(:num)', 'NotificationsController::markRead/$1');
    $routes->post('notifications/mark-all-read', 'NotificationsController::markAllRead');

    
    $routes->get('venues', 'VenueController::index');
    $routes->get('venues/create', 'VenueController::create');
    $routes->post('venues/store', 'VenueController::store');
    $routes->get('venues/edit/(:num)', 'VenueController::edit/$1');
    $routes->post('venues/update/(:num)', 'VenueController::update/$1');
    $routes->get('venues/delete/(:num)', 'VenueController::delete/$1');
    $routes->post('venues/upload-image', 'VenueController::uploadImage');

    $routes->get('packages-view', 'PackageController::index');
    $routes->get('packages/create', 'PackageController::create');
    $routes->post('packages/store', 'PackageController::store');
    $routes->get('packages/edit/(:num)', 'PackageController::edit/$1');
    $routes->post('packages/update/(:num)', 'PackageController::update/$1');
    $routes->get('packages/delete/(:num)', 'PackageController::delete/$1');

    $routes->get('addons', 'AddonController::index');
    $routes->get('addons/create', 'AddonController::create');
    $routes->post('addons/store', 'AddonController::store');
    $routes->get('addons/edit/(:num)', 'AddonController::edit/$1');
    $routes->post('addons/update/(:num)', 'AddonController::update/$1');
    $routes->get('addons/delete/(:num)', 'AddonController::delete/$1');

    $routes->post('packages/link-venue', 'PackageController::linkVenue');
    $routes->post('packages/unlink-venue/(:num)', 'PackageController::unlinkVenue/$1');
    $routes->post('packages/set-primary-venue', 'PackageController::setPrimaryVenue');

    $routes->post('admin/users/add-to-group/(:num)', 'UsersController::addToGroup/$1');
    $routes->post('admin/users/remove-from-group/(:num)', 'UsersController::removeFromGroup/$1');
    $routes->get('admin/users', 'UsersController::index');
    $routes->get('admin/users/(:num)', 'UsersController::show/$1');
    $routes->post('admin/users/toggle-status/(:num)', 'UsersController::toggleStatus/$1');
    $routes->post('admin/users/make-admin/(:num)', 'UsersController::makeAdmin/$1');
    $routes->post('admin/users/make-client/(:num)', 'UsersController::makeClient/$1');
    $routes->post('admin/users/update/(:num)', 'UsersController::update/$1');
    $routes->post('admin/users/delete/(:num)', 'UsersController::delete/$1');

    $routes->get('admin/client-transactions', 'ClientTransactionsController::index');
    $routes->get('admin/client-transactions/(:num)', 'ClientTransactionsController::show/$1');
    $routes->get('admin/client-transactions/print/(:num)', 'ClientTransactionsController::printHistory/$1');

    $routes->get('admin/contracts', 'AdminContractsController::index');
    $routes->get('admin/contracts/create', 'AdminContractsController::create');
    $routes->post('admin/contracts/store', 'AdminContractsController::store');
    $routes->post('admin/contracts/preview/(:num)', 'AdminContractsController::preview/$1');
    $routes->post('admin/contracts/send/(:num)', 'AdminContractsController::send/$1');
    $routes->post('admin/contracts/delete/(:num)', 'AdminContractsController::delete/$1');
    $routes->get('admin/contracts/download/(:num)', 'AdminContractsController::download/$1');
    $routes->post('admin/contracts/upload-signed/(:num)', 'AdminContractsController::uploadSigned/$1');
    $routes->post('admin/contracts/send_debug/(:num)', 'AdminContractsController::send_debug/$1');
    
});