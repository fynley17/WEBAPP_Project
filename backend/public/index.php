<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\AssignmentController;

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// User routes
if ($uri === '/api/users' && $method === 'GET') {
    $controller = new UserController();
    $controller->getUsers();
} else if (preg_match('/^\/api\/users\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new UserController();
    $controller->getUserByID($matches[1]);
} else if (preg_match('/^\/api\/users\/([^\/]+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new UserController();
    $controller->getUserByUsername($matches[1]);
} else if (preg_match('/^\/api\/users\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    $controller = new UserController();
    $controller->deleteUser($matches[1]);
} else if ($uri === '/api/users' && $method === 'POST') {
    $controller = new UserController();
    $controller->createUser();
} else if (preg_match('/^\/api\/users\/(\d+)$/', $uri, $matches) && $method === 'PATCH') {
    $controller = new UserController();
    $controller->updateUser($matches[1]);
}
// Course routes
else if ($uri === '/api/courses' && $method === 'GET') {
    $controller = new CourseController();
    $controller->getCourses();
} else if (preg_match('/^\/api\/courses\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new CourseController();
    $controller->getCourseByID($matches[1]);
} else if (preg_match('/^\/api\/courses\/([^\/]+)$/', $uri, $matches) && $method === 'GET') {
    $controller = new CourseController();
    $controller->getCourseByTitle($matches[1]);
} else if (preg_match('/^\/api\/courses\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
    $controller = new CourseController();
    $controller->deleteCourse($matches[1]);
} else if ($uri === '/api/courses' && $method === 'POST') {
    $controller = new CourseController();
    $controller->createCourse();
} else if (preg_match('/^\/api\/courses\/(\d+)$/', $uri, $matches) && $method === 'PATCH') {
    $controller = new CourseController();
    $controller->updateCourse($matches[1]);
}
// Assignment routes
else if ($uri === '/api/assignments' && $method === 'GET') {
    $controller = new AssignmentController();
    $controller->getAssignments();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
