<?php

namespace App\Controllers;

use App\Models\Assignment;

require_once __DIR__ . '/../helpers/Email.php';

class AssignmentController extends Controller
{
    // Retrieve all assignments
    public function getAssignments()
    {
        $assignments = Assignment::all();
        $this->jsonResponse($assignments);
    }

    // Retrieve an assignment by ID
    public function getAssignmentByID($id)
    {
        $assignment = Assignment::findByID($id);
        if ($assignment) {
            $this->jsonResponse($assignment);
        } else {
            $this->jsonResponse(['error' => 'assignment not found'], 404);
        }
    }

    // Retrieve an assignment by title
    public function getAssignmentByUsername($assignmentName)
    {
        $assignment = Assignment::findByUsername($assignmentName);
        if ($assignment) {
            $this->jsonResponse($assignment);
        } else {
            $this->jsonResponse(['error' => 'assignment not found'], 404);
        }
    }

    // Create a new assignment
    public function createAssignment()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = Assignment::create($data);
            if ($result) {
                // Send an enrolment email upon successful creation
                \App\Helpers\Email::Enroll($data['userID'], $data['courseID']);
                $this->jsonResponse(['message' => 'Assignment created successfully'], 201);
            } else {
                $this->jsonResponse(['error' => 'Failed to create assignment'], 400);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    // Update an existing assignment
    public function updateAssignment($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $updated = Assignment::update($id, $data);
        if ($updated) {
            $this->jsonResponse(['message' => 'Assignment updated successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to update assignment'], 500);
        }
    }

    // Delete an assignment
    public function deleteAssignment($id)
    {
        $deleted = Assignment::delete($id);
        if ($deleted) {
            $this->jsonResponse(['message' => 'Assignment deleted successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to delete assignment'], 500);
        }
    }
}
