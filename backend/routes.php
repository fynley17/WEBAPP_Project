<?php

use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\AssignmentController;
use App\Controllers\AuthController;
use App\Router;

// Allow requests from the frontend origin
header("Access-Control-Allow-Origin: http://127.0.0.1:5174");
// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No content
    exit;
}

// Initialise the router
$router = new Router();

// User routes
$router->addRoute('GET', '/^\\/api\\/users$/', [new UserController(), 'getUsers']);
$router->addRoute('GET', '/^\\/api\\/users\\/(\\d+)$/', [new UserController(), 'getUserByID']);
$router->addRoute('GET', '/^\\/api\\/users\\/([^\\/]+)$/', [new UserController(), 'getUserByUsername']);
$router->addRoute('DELETE', '/^\\/api\\/users\\/(\\d+)$/', [new UserController(), 'deleteUser']);
$router->addRoute('POST', '/^\\/api\\/users$/', [new UserController(), 'createUser']);
$router->addRoute('PATCH', '/^\\/api\\/users\\/(\\d+)$/', [new UserController(), 'updateUser']);

// Course routes
$router->addRoute('GET', '/^\\/api\\/courses$/', [new CourseController(), 'getCourses']);
$router->addRoute('GET', '/^\\/api\\/courses\\/(\\d+)$/', [new CourseController(), 'getCourseByID']);
$router->addRoute('GET', '/^\\/api\\/courses\\/([^\\/]+)$/', [new CourseController(), 'getCourseByTitle']);
$router->addRoute('DELETE', '/^\\/api\\/courses\\/(\\d+)$/', [new CourseController(), 'deleteCourse']);
$router->addRoute('POST', '/^\\/api\\/courses$/', [new CourseController(), 'createCourse']);
$router->addRoute('PATCH', '/^\\/api\\/courses\\/(\\d+)$/', [new CourseController(), 'updateCourse']);
$router->addRoute('GET', '/^\\/api\\/assignedUsers\\/(\\d+)$/', [new CourseController(), 'assignedUsers']);

// Assignment routes
$router->addRoute('GET', '/^\\/api\\/assignments$/', [new AssignmentController(), 'getAssignments']);
$router->addRoute('GET', '/^\\/api\\/assignments\\/(\\d+)$/', [new AssignmentController(), 'getAssignmentByID']);
$router->addRoute('GET', '/^\\/api\\/assignments\\/([^\\/]+)$/', [new AssignmentController(), 'getAssignmentByUsername']);
$router->addRoute('DELETE', '/^\\/api\\/assignments\\/(\\d+)$/', [new AssignmentController(), 'deleteAssignment']);
$router->addRoute('POST', '/^\\/api\\/assignments$/', [new AssignmentController(), 'createAssignment']);
$router->addRoute('PATCH', '/^\\/api\\/assignments\\/(\\d+)$/', [new AssignmentController(), 'updateAssignment']);

//authentication
$router->addRoute('POST', '/^\\/api\\/login$/', [new AuthController(), 'login']);
$router->addRoute('POST', '/^\\/api\\/forgot-password$/', [new AuthController(), 'forgotPassword']);
$router->addRoute('POST', '/^\\/api\\/reset-password$/', [new AuthController(), 'resetPassword']);

return $router;
