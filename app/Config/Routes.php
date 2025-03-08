<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('/login', 'AuthController::login', ['as' => 'login']);
$routes->post('/login', 'AuthController::attemptLogin', ['as' => 'attemptLogin']);
$routes->get('/logout', 'AuthController::logout', ['as' => 'logout']);

// Dashboard Route
$routes->get('/dashboard', 'DashboardController::index', ['as' => 'dashboard']);

// Grades Routes
$routes->get('/grades', 'GradesController::index', ['as' => 'grades']);

// Student Results Routes - ABSOLUTELY NO FILTERS
$routes->get('/student-results', 'StudentResultsController::index', ['as' => 'student.results']);
$routes->get('/student-results/(:num)', 'StudentResultsController::show/$1', ['as' => 'student.results.show']);
$routes->get('/api/student-results/(:num)', 'StudentResultsController::getStudentResults/$1', ['as' => 'api.student.results']);

// Alternative routes with index.php prefix - ABSOLUTELY NO FILTERS
$routes->get('/index.php/student-results', 'StudentResultsController::index');
$routes->get('/index.php/student-results/(:num)', 'StudentResultsController::show/$1');
$routes->get('/index.php/api/student-results/(:num)', 'StudentResultsController::getStudentResults/$1');

// Shield's built-in routes
service('auth')->routes($routes);
