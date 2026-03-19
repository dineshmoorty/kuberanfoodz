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
$routes->get('/admin/settings', 'Settings::list');
$routes->get('/admin/settings/add', 'Settings::add');
$routes->post('/admin/settings/create', 'Settings::create');
$routes->get('/admin/settings/edit/(:num)', 'Settings::edit/$1');
$routes->post('/admin/settings/update/(:num)', 'Settings::update/$1');
$routes->post('/admin/settings/delete/(:num)', 'Settings::delete/$1');
