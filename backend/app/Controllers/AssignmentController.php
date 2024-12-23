<?php
namespace App\Controllers;

use App\Models\Assignment;

class AssignmentController extends Controller {
    public function getAssignments() {
        $assignments = Assignment::all();
        $this->jsonResponse($assignments);
    }
}