<?php

use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\AssignmentController;
use App\Controllers\AuthController;
use App\Router;

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

// Assignment routes
$router->addRoute('GET', '/^\\/api\\/assignments$/', [new AssignmentController(), 'getAssignments']);
$router->addRoute('GET', '/^\\/api\\/assignments\\/(\\d+)$/', [new AssignmentController(), 'getAssignmentByID']);
$router->addRoute('GET', '/^\\/api\\/assignments\\/([^\\/]+)$/', [new AssignmentController(), 'getAssignmentByUsername']);
$router->addRoute('DELETE', '/^\\/api\\/assignments\\/(\\d+)$/', [new AssignmentController(), 'deleteAssignment']);
$router->addRoute('POST', '/^\\/api\\/assignments$/', [new AssignmentController(), 'createAssignment']);
$router->addRoute('PATCH', '/^\\/api\\/assignments\\/(\\d+)$/', [new AssignmentController(), 'updateAssignment']);


return $router;