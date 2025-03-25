<?php

namespace App\Controllers;

use App\Models\User;

require_once __DIR__ . '/../helpers/Jwt.php'; // Adjust path if needed
require_once __DIR__ . '/../helpers/Email.php';


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

            if (!isset($data['username']) || !isset($data['password'])) {
                $this->jsonResponse(['error' => 'Username and password are required'], 400);
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
                    'access_level' => $user['accessLevel'],
                    'username' => $user['username'],
                    'userID' => $user['userID'],
                    'exp' => time() + 3600 // Expires in 1 hour
                ];

                // Generate JWT token
                $token = \App\Helpers\Jwt::generate_jwt($payload);

                // Return the token to the client
                $this->jsonResponse(['message' => 'Login successful', 'token' => $token, 'accessLevel' => $user['accessLevel'], 'username' => $user['username'], 'userID' => $user['userID']]);
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

    public function forgotPassword()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            error_log("Forgot Password API called");
            error_log(print_r($data, true));


            if (!$data) {
                error_log("No data received in forgotPassword request");
                $this->jsonResponse(['error' => 'No request data received'], 400);
                return;
            }

            if (empty($data['username'])) {
                error_log("Username field is empty");
                $this->jsonResponse(['error' => 'Username is required'], 400);
                return;
            }


            $username = $data['username'];

            $user = User::findByUsername($username);
            if (!$user) {
                error_log("User not found: " . $username);
                $this->jsonResponse(['error' => 'User not found'], 404);
                return;
            }


            // Generate a unique token
            $token = bin2hex(random_bytes(16));

            // Store the token in the database with an expiration time
            $user['reset_token'] = $token;
            $user['token_expiration'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
            User::update($user['userID'], $user);

            // Generate the password reset URL
            $resetUrl = "https://ws381211-wad.remote.ac/password-reset?token=$token";

            \App\Helpers\Email::Reset($user['email'], $resetUrl);

            $this->jsonResponse(['message' => 'Password reset link has been sent to your email', $user['email']]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }

    public function resetPassword()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            error_log("Reset Password API called");
            error_log(print_r($data, true));
            error_log("Request data: " . json_encode($data)); // Log request data to error log

            if (!$data || empty($data['token']) || empty($data['password'])) {
                $this->jsonResponse(['error' => 'Token and password are required'], 400);
                return;
            }

            $token = $data['token'];
            $password = $data['password'];

            $user = User::findByResetToken($token);

            if (!$user || strtotime($user['token_expiration']) < time()) {
                $this->jsonResponse(['error' => 'Invalid or expired token'], 400);
                return;
            }

            // Update the user's password
            $user['password'] = $password; // Ensure the password is hashed
            $user['reset_token'] = null; // Clear the reset token
            $user['token_expiration'] = null; // Clear the token expiration
            $updateResult = User::update($user['userID'], $user);
            error_log("Update Result: " . json_encode($updateResult)); // Log the update result

            $this->jsonResponse(['message' => 'Password has been reset successfully']);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }
}
