<?php

namespace App\Controllers;

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Controller
{
    /**
     * Sends a JSON response to the client.
     *
     * @param mixed $data The data to be sent as a JSON response.
     * @param int $status The HTTP status code (default is 200 OK).
     */
    public function jsonResponse($data, $status = 200)
    {
        // Set the content type to JSON
        header("Content-Type: application/json");

        // Set the HTTP response status code
        http_response_code($status);

        // Encode the data into JSON format and print it
        echo json_encode($data, JSON_PRETTY_PRINT);

        // Terminate script execution to ensure no extra output is sent
        exit;
    }
}
