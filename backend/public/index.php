<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;

$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/api/users') {
    $controller = new UserController();
    $controller->getUsers();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
