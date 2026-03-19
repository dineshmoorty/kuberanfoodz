<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Login
$routes->get('/admin/login', 'Auth::adminLogin');
$routes->post('/admin/login', 'Auth::adminAuthenticate');
$routes->get('/admin/profile', 'Auth::profile');
$routes->get('/admin/profiles', 'Auth::profile');
$routes->post('/admin/profiles/create', 'Auth::createProfile');
$routes->post('/admin/profiles/update/(:num)', 'Auth::updateProfile/$1');
$routes->post('/admin/profiles/delete/(:num)', 'Auth::deleteProfile/$1');
$routes->get('/admin/roles', 'Roles::list');
$routes->post('/admin/roles/create', 'Roles::create');
$routes->post('/admin/roles/update/(:num)', 'Roles::update/$1');
$routes->post('/admin/roles/delete/(:num)', 'Roles::delete/$1');
$routes->get('/admin/logout', 'Auth::logout');

$routes->get('/admin/dashboard', 'Dashboard::index');
$routes->get('/sub-admin/dashboard', 'SubAdminDashboard::index');
$routes->get('/manager/dashboard', 'ManagerDashboard::index');

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

// Categories management
$routes->get('/admin/categories', 'Categories::list');
$routes->post('/admin/categories/create', 'Categories::create');
$routes->get('/admin/categories/edit/(:num)', 'Categories::edit/$1');
$routes->post('/admin/categories/update/(:num)', 'Categories::update/$1');
$routes->post('/admin/categories/delete/(:num)', 'Categories::delete/$1');
