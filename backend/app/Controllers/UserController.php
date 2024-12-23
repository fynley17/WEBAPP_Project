<?php
namespace App\Controllers;

use App\Models\User;

class UserController extends Controller {

    // Get all users
    public function getUsers(){
        $users = User::all();
        $this->jsonResponse($users);
    }

    // Get by ID
    public function getUserByID($id){
        $user = User::findByID($id);
        if ($user) {
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['error' => 'Assignment not found'], 404);
        }
    }

    // Get by username
    public function getUserByUsername($username){
        $user = User::findByUsername($username);
        if ($user) {
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['error' => 'Assignment not found'], 404);
        }
    }

    // Create user 
    public function createUser() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = User::create($data);
            if ($result) {
                $this->jsonResponse(['message' => 'User created successfully'], 201);
            } else {
                $this->jsonResponse(['error' => 'Failed to create user'], 400);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    // Delete User
    public function deleteUser($id) {
        $deleted = User::delete($id);
        if ($deleted) {
            $this->jsonResponse(['message' => 'Assignment deleted successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to delete assignment'], 500);
        }
    }
}