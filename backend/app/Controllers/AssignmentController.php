<?php

namespace App\Controllers;

use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function getAssignments()
    {
        $assignments = Assignment::all();
        $this->jsonResponse($assignments);
    }

    // Get by ID
    public function getAssignmentByID($id)
    {
        $assignment = Assignment::findByID($id);
        if ($assignment) {
            $this->jsonResponse($assignment);
        } else {
            $this->jsonResponse(['error' => 'assignment not found'], 404);
        }
    }

    // Get by title
    public function getAssignmentByTitle($assignmentName)
    {
        $assignment = Assignment::findByTitle($assignmentName);
        if ($assignment) {
            $this->jsonResponse($assignment);
        } else {
            $this->jsonResponse(['error' => 'assignment not found'], 404);
        }
    }

    // Create assignment 
    public function createAssignment()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = Assignment::create($data);
            if ($result) {
                $this->jsonResponse(['message' => 'Assignment created successfully'], 201);
            } else {
                $this->jsonResponse(['error' => 'Failed to create assignment'], 400);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    // Update a assignment
    public function updateAssignment($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $updated = Assignment::update($id, $data);
        if ($updated) {
            $this->jsonResponse(['message' => 'assignment updated successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to update assignassignmentment'], 500);
        }
    }

    // Delete Assignment
    public function deleteAssignment($id)
    {
        $deleted = Assignment::delete($id);
        if ($deleted) {
            $this->jsonResponse(['message' => 'assignment deleted successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to delete assignment'], 500);
        }
    }
}
