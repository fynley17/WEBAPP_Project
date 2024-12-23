<?php
namespace App\Controllers;

use App\Models\User;

class UserController extends Controller {

    // Get all users
    public function getUsers(){
        $users = User::all();
        $this->jsonResponse($users);
    }

    // Get by username
    public function getUserByUsername($username){
        $user = User::find($username);
        if ($user) {
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['error' => 'Assignment not found'], 404);
        }
    }
}