<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Login
$routes->get('/admin/login', 'Auth::adminLogin');
$routes->post('/admin/login', 'Auth::adminAuthenticate');
$routes->get('/admin/logout', 'Auth::logout');

$routes->get('/admin/dashboard', 'Dashboard::index');

// Company settings
$routes->get('/admin/settings', 'Settings::list');
$routes->get('/admin/settings/add', 'Settings::add');
$routes->post('/admin/settings/create', 'Settings::create');
$routes->get('/admin/settings/edit/(:num)', 'Settings::edit/$1');
$routes->post('/admin/settings/update/(:num)', 'Settings::update/$1');
$routes->post('/admin/settings/delete/(:num)', 'Settings::delete/$1');

// Days management
$routes->get('/admin/days', 'Days::list');
$routes->post('/admin/days/create', 'Days::create');
$routes->post('/admin/days/update/(:num)', 'Days::update/$1');
$routes->post('/admin/days/delete/(:num)', 'Days::delete/$1');
