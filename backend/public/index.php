<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\AssignmentController;

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// User routes
if ($uri === '/api/users' && $method === 'GET' ) {
    $controller = new UserController();
    $controller->getUsers(); 
} else if (preg_match('/^\/api\/users\/([^\/]+)$/', $uri, $matches) && $method === 'GET') {
    $username = $matches[1]; // Extract the username from the URL
    $controller = new UserController();
    $controller->getUserByUsername($username);
}
// Course routes
else if ($uri === '/api/courses' && $method === 'GET'){
    $controller = new CourseController();
    $controller->getCourses();
}
// Assignment routes
else if ($uri === '/api/assignments' && $method === 'GET'){
    $controller = new AssignmentController();
    $controller->getAssignments();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
