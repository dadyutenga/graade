<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('/login', 'AuthController::login', ['as' => 'login']);
$routes->post('/login', 'AuthController::attemptLogin', ['as' => 'attemptLogin']);
// Register route removed as it will be handled by Shield CLI
$routes->get('/logout', 'AuthController::logout', ['as' => 'logout']);

// Dashboard Route (Protected)
$routes->get('/dashboard', 'DashboardController::index', ['as' => 'dashboard']);

// Grades Routes (Protected)
$routes->get('/grades', 'GradesController::index', ['as' => 'grades']);

// Student Results Routes (Protected)
$routes->get('/student-results', 'StudentResultsController::index', ['as' => 'student.results']);
$routes->get('/student-results/(:num)', 'StudentResultsController::show/$1', ['as' => 'student.results.show']);
$routes->get('/api/student-results/(:num)', 'StudentResultsController::getStudentResults/$1', ['as' => 'api.student.results']);

// Shield's built-in routes (for password reset, etc.)
service('auth')->routes($routes);
