<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\AssignmentController;

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/api/users' && $method === 'GET' ) {
    $controller = new UserController();
    $controller->getUsers();
} else if ($uri === '/api/courses' && $method === 'GET'){
    $controller = new CourseController();
    $controller->getCourses();
} else if ($uri === '/api/assignments' && $method === 'GET'){
    $controller = new AssignmentController();
    $controller->getAssignments();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
