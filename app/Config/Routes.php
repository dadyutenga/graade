<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('/login', 'AuthController::login', ['as' => 'login']);
$routes->post('/login', 'AuthController::attemptLogin', ['as' => 'attemptLogin']);
$routes->get('/register', 'AuthController::register', ['as' => 'register']);
$routes->post('/register', 'AuthController::attemptRegister', ['as' => 'attemptRegister']);
$routes->get('/logout', 'AuthController::logout', ['as' => 'logout']);

// Dashboard Route (Protected)
$routes->get('/dashboard', 'DashboardController::index', ['as' => 'dashboard']);

// Shield's built-in routes (for password reset, etc.)
service('auth')->routes($routes);
