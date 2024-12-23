<?php
namespace App\Controller;

class Controller {
    public function jsonResponse($data, $status = 200) {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
