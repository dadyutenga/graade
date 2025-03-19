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

// API endpoints for getting data
$routes->post('/student-results/search', 'StudentResultsController::search', ['as' => 'student.results.search']);
$routes->post('/student-results/studentresult', 'StudentResultsController::studentresult', ['as' => 'student.results.studentresult']);
$routes->post('/student-results/get-student-current-result', 'StudentResultsController::getStudentCurrentResult', ['as' => 'student.results.current']);
$routes->get('/student-results/print/(:num)/(:num)', 'StudentResultsController::printResult/$1/$2', ['as' => 'student.results.print']);
$routes->post('/student-results/get-student-by-class-batch', 'StudentResultsController::getStudentByClassBatch', ['as' => 'student.results.by.class']);
$routes->post('/student-results/get-exam-group-by-student', 'StudentResultsController::getExamGroupByStudent', ['as' => 'student.results.exam.group']);
$routes->post('/student-results/generate-marksheet', 'StudentResultsController::generatemarksheet', ['as' => 'student.results.marksheet']);
$routes->post('/student-results/search-by-admission-no', 'StudentResultsController::searchByAdmissionNo', ['as' => 'student.results.search.admission']);
$routes->post('/student-results/get-exam-result', 'StudentResultsController::getExamResult', ['as' => 'student.results.exam.result']);
$routes->post('/student-results/get-exam-results', 'StudentResultsController::getExamResults', ['as' => 'student.results.exam.results']);

// Alternative routes with index.php prefix - ABSOLUTELY NO FILTERS
$routes->get('/index.php/student-results', 'StudentResultsController::index');
$routes->get('/index.php/student-results/(:num)', 'StudentResultsController::show/$1');
$routes->get('/index.php/api/student-results/(:num)', 'StudentResultsController::getStudentResults/$1');

// New Alternative routes with index.php prefix
$routes->post('/index.php/student-results/search', 'StudentResultsController::search');
$routes->post('/index.php/student-results/studentresult', 'StudentResultsController::studentresult');
$routes->post('/index.php/student-results/get-student-current-result', 'StudentResultsController::getStudentCurrentResult');
$routes->get('/index.php/student-results/print/(:num)/(:num)', 'StudentResultsController::printResult/$1/$2');
$routes->post('/index.php/student-results/get-student-by-class-batch', 'StudentResultsController::getStudentByClassBatch');
$routes->post('/index.php/student-results/get-exam-group-by-student', 'StudentResultsController::getExamGroupByStudent');
$routes->post('/index.php/student-results/generate-marksheet', 'StudentResultsController::generatemarksheet');
$routes->post('/index.php/student-results/search-by-admission-no', 'StudentResultsController::searchByAdmissionNo');
$routes->post('/index.php/student-results/get-exam-result', 'StudentResultsController::getExamResult');
$routes->post('/index.php/student-results/get-exam-results', 'StudentResultsController::getExamResults');

// Sub-endpoints for handling sections and exam groups
$routes->post('/sections/get-by-class', 'SectionsController::getByClass');
$routes->post('/exams/get-by-exam-group', 'ExamsController::getByExamGroup');

// Also add index.php versions
$routes->post('/index.php/sections/get-by-class', 'SectionsController::getByClass');
$routes->post('/index.php/exams/get-by-exam-group', 'ExamsController::getByExamGroup');

// Shield's built-in routes
service('auth')->routes($routes);
