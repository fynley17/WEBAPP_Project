<?php
namespace App\Controllers;

use App\Models\User;

class UserController extends Controller {
    public function getUsers(){
        $users = User::all();
        $this->jsonResponse($users);
    }
}