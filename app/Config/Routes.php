<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Pages::home', ['as' => 'home']);
$routes->get('about', 'Pages::about', ['as' => 'about']);

// Authentication
$routes->get('login', 'Auth::login', ['as' => 'login']);
$routes->post('login', 'Auth::attempt', ['as' => 'login.attempt']);
$routes->post('logout', 'Auth::logout', ['as' => 'logout']);

// Customers
$routes->get('customers', 'Customers::index', ['as' => 'customers', 'filter' => 'auth']);
$routes->get('customers/new', 'Customers::new', ['as' => 'customers.new', 'filter' => 'auth']);
$routes->post('customers/new', 'Customers::create', ['as' => 'customers.create', 'filter' => 'auth']);
$routes->get('customers/edit/(:num)', 'Customers::edit/$1', ['as' => 'customers.edit', 'filter' => 'auth']);
$routes->post('customers/edit/(:num)', 'Customers::update/$1', ['as' => 'customers.update', 'filter' => 'auth']);

// Users
$routes->get('users', 'Users::index', ['as' => 'users', 'filter' => 'auth']);
$routes->get('users/new', 'Users::new', ['as' => 'users.new', 'filter' => 'auth']);
$routes->post('users/new', 'Users::create', ['as' => 'users.create', 'filter' => 'auth']);
$routes->get('users/edit/(:num)', 'Users::edit/$1', ['as' => 'users.edit', 'filter' => 'auth']);
$routes->post('users/edit/(:num)', 'Users::update/$1', ['as' => 'users.update', 'filter' => 'auth']);
