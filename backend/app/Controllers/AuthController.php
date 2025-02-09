<?php

namespace App\Controllers;

use App\Models\User;

require_once __DIR__ . '/../helpers/Jwt.php'; // Adjust path if needed

class AuthController extends Controller
{
    // Login method to authenticate users
    public function login()
    {
        try {
            // Get the input data (username and password)
            $data = json_decode(file_get_contents("php://input"), true);

            // Check if data is valid
            if (!$data) {
                $this->jsonResponse(['error' => 'Invalid JSON input'], 400);
                return;
            }

            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';

            // Check if username and password are provided
            if (empty($username) || empty($password)) {
                $this->jsonResponse(['error' => 'Username and password are required'], 400);
                return;
            }

            // Fetch user by username
            $user = User::findByUsername($username);

            // If user not found
            if (!$user) {
                $this->jsonResponse(['error' => 'User not found'], 404);
                return;
            }

            // Validate user and password
            if (password_verify($password, $user['password'])) {
                // Create JWT payload with user details
                $payload = [
                    'user_id' => $user['username'],
                    'username' => $user['username'],
                    'access_level' => $user['accessLevel'],
                    'username' => $username['username'],
                    'exp' => time() + 3600 // Expires in 1 hour
                ];

                // Generate JWT token
                $token = \App\Helpers\Jwt::generate_jwt($payload);

                // Return the token to the client
                $this->jsonResponse(['message' => 'Login successful', 'token' => $token, 'accessLevel' => $user['accessLevel'], 'username' => $user['username']]);
            } else {
                // If password is incorrect
                $this->jsonResponse(['error' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the login process
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }

    // A method to check if the token is valid
    public function verifyToken()
    {
        try {
            // Get the authorization header
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? '';

            // Check if token is provided
            if (empty($token)) {
                // If token is missing, return bad request
                $this->jsonResponse(['error' => 'Token is missing'], 400);
                return;
            }

            // Verify the token using your custom verify_jwt function
            $user_data = \App\Helpers\Jwt::verify_jwt($token);

            if ($user_data) {
                // Token is valid, user is authenticated
                $this->jsonResponse(['message' => 'Token is valid', 'user' => $user_data]);
            } else {
                // If token is invalid, return unauthorized
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the token verification process
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }
}
