<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Pages::home', ['as' => 'home']);
$routes->get('about', 'Pages::about', ['as' => 'about']);

// Customers
$routes->get('customers', 'Customers::index', ['as' => 'customers']);
$routes->get('customers/new', 'Customers::new', ['as' => 'customers.new']);
$routes->post('customers/new', 'Customers::create', ['as' => 'customers.create']);
$routes->get('customers/edit/(:num)', 'Customers::edit/$1', ['as' => 'customers.edit']);
$routes->post('customers/edit/(:num)', 'Customers::update/$1', ['as' => 'customers.update']);

// Users
$routes->get('users', 'Users::index', ['as' => 'users']);
$routes->get('users/new', 'Users::new', ['as' => 'users.new']);
$routes->post('users/new', 'Users::create', ['as' => 'users.create']);
$routes->get('users/edit/(:num)', 'Users::edit/$1', ['as' => 'users.edit']);
$routes->post('users/edit/(:num)', 'Users::update/$1', ['as' => 'users.update']);
