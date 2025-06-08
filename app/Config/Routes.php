<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/verify', 'Auth::verify');
$routes->get('/guest', 'Auth::guest');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');
$routes->get('/tenant', 'Tenant::index');
$routes->post('api/user/login', 'UserController::login');
$routes->post('api/crud/create', 'FmsCrudController::create');
$routes->post('api/crud/update', 'FmsCrudController::update');
$routes->post('api/crud/delete', 'FmsCrudController::delete');
$routes->post('api/crud/filter', 'FmsCrudController::filter');
$routes->post('api/other/call', 'FmsOtherController::call');
$routes->post('api/other/get-user-by-key', 'FmsOtherController::getUserByKey');
$routes->post('api/other/update-user-password-by-key', 'FmsOtherController::updateUserPasswordByKey');
$routes->get('/reset-password', 'ResetPassword::index');
$routes->post('/reset-password', 'ResetPassword::send');
