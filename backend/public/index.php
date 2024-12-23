<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\CourseController;

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/api/users' && $method === 'GET' ) {
    $controller = new UserController();
    $controller->getUsers();
}else if ($uri === '/api/courses' && $method === 'GET'){
    $controller = new CourseController();
    $controller->getCourses();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
