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

// Product Routes (Protected)
$routes->get('/products', 'ProductController::index', ['as' => 'products']);
$routes->get('/products/new', 'ProductController::new', ['as' => 'products.new']);
$routes->post('/products', 'ProductController::create', ['as' => 'products.create']);
$routes->get('/products/edit/(:num)', 'ProductController::edit/$1', ['as' => 'products.edit']);
$routes->post('/products/update/(:num)', 'ProductController::update/$1', ['as' => 'products.update']);
$routes->post('/products/delete/(:num)', 'ProductController::delete/$1', ['as' => 'products.delete']);

// Student Results Routes (Protected)
$routes->get('/student-results', 'StudentResultsController::index', ['as' => 'student.results']);
$routes->get('/student-results/(:num)', 'StudentResultsController::show/$1', ['as' => 'student.results.show']);
$routes->get('/api/student-results/(:num)', 'StudentResultsController::getStudentResults/$1', ['as' => 'api.student.results']);

// Shield's built-in routes (for password reset, etc.)
service('auth')->routes($routes);
