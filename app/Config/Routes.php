<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Pages::home', ['as' => 'home']);
$routes->get('about', 'Pages::about', ['as' => 'about']);
$routes->get('customers', 'Customers::index', ['as' => 'customers']);
$routes->get('users', 'Users::index', ['as' => 'users']);
