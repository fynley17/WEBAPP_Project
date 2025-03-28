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
            $data = json_decode(file_get_contents("php://input"), true);

            // Validate input data
            if (!$data || !isset($data['username']) || !isset($data['password'])) {
                $this->jsonResponse(['error' => 'Username and password are required'], 400);
                return;
            }

            $username = $data['username'];
            $password = $data['password'];

            $user = User::findByUsername($username);

            // Check if user exists
            if (!$user) {
                $this->jsonResponse(['error' => 'Incorrect username or password'], 401);
                return;
            }

            // Check if the user is locked out due to too many failed attempts
            if (User::isLockedOut($user['userID'])) {
                $this->jsonResponse(['error' => 'Your account is locked due to too many failed login attempts. Please try again in 5 minutes.'], 403);
                return;
            }

            // Validate password
            if (password_verify($password, $user['password'])) {
                // Reset failed login attempts upon successful authentication
                User::resetFailedAttempts($user['userID']);

                // Create a JWT token with user details
                $payload = [
                    'access_level' => $user['accessLevel'],
                    'username' => $user['username'],
                    'userID' => $user['userID'],
                    'exp' => time() + 3600 // Token expires in 1 hour
                ];

                $token = \App\Helpers\Jwt::generate_jwt($payload);

                $this->jsonResponse(['success' => true, 'message' => 'Login successful', 'token' => $token, 'accessLevel' => $user['accessLevel'], 'username' => $user['username'], 'userID' => $user['userID']]);
            } else {
                // Increment failed login attempts
                User::incrementFailedAttempts($user['userID']);

                // Lock the account if failed attempts reach 3
                if ($user['failed_attempts'] + 1 >= 3) {
                    User::lockUser($user['userID']);
                    $this->jsonResponse(['error' => 'Your account is locked due to too many failed login attempts. Please try again in 5 minutes.'], 403);
                } else {
                    $this->jsonResponse(['error' => 'Incorrect username or password'], 401);
                }
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    // Method to verify if a provided JWT token is valid
    public function verifyToken()
    {
        try {
            // Get the authorisation header
            $headers = getallheaders();
            $token = $headers['Authorization'] ?? '';

            // Check if token is provided
            if (empty($token)) {
                $this->jsonResponse(['error' => 'Token is missing'], 400);
                return;
            }

            // Verify the token
            $user_data = \App\Helpers\Jwt::verify_jwt($token);

            if ($user_data) {
                // Token is valid
                $this->jsonResponse(['message' => 'Token is valid', 'user' => $user_data]);
            } else {
                $this->jsonResponse(['error' => 'Unauthorised'], 401);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }

    // Method to handle forgotten password requests
    public function forgotPassword()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || empty($data['username'])) {
                $this->jsonResponse(['error' => 'Username is required'], 400);
                return;
            }

            $username = $data['username'];
            $user = User::findByUsername($username);

            if (!$user) {
                $this->jsonResponse(['error' => 'User not found'], 404);
                return;
            }

            // Generate a unique token for password reset
            $token = bin2hex(random_bytes(16));

            // Store the reset token and expiration time
            $user['reset_token'] = $token;
            $user['token_expiration'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
            User::update($user['userID'], $user);

            // Generate the password reset URL
            $resetUrl = "https://ws381211-wad.remote.ac/password-reset?token=$token";

            // Send password reset email
            \App\Helpers\Email::Reset($user['email'], $resetUrl);

            $this->jsonResponse(['message' => 'Password reset link has been sent to your email']);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }

    // Method to reset the user's password
    public function resetPassword()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || empty($data['token']) || empty($data['password'])) {
                $this->jsonResponse(['error' => 'Token and password are required'], 400);
                return;
            }

            $token = $data['token'];
            $password = $data['password'];

            // Find user by reset token
            $user = User::findByResetToken($token);

            if (!$user || strtotime($user['token_expiration']) < time()) {
                $this->jsonResponse(['error' => 'Invalid or expired token'], 400);
                return;
            }

            // Update the user's password and clear the reset token
            $user['password'] = $password;
            $user['reset_token'] = null;
            $user['token_expiration'] = null;
            User::update($user['userID'], $user);

            $this->jsonResponse(['message' => 'Password has been reset successfully']);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'An error occurred while processing the request', 'details' => $e->getMessage()], 500);
        }
    }
}
