<?php

namespace App\Controllers;

use App\Models\Course;

class CourseController extends Controller
{

    // Get all courses
    public function getCourses()
    {
        $courses = Course::all();
        $this->jsonResponse($courses);
    }

    // Get by ID
    public function getCourseByID($id)
    {
        $course = Course::findByID($id);
        if ($course) {
            $this->jsonResponse($course);
        } else {
            $this->jsonResponse(['error' => 'course not found'], 404);
        }
    }

    // Get by title
    public function getCourseByTitle($courseName)
    {
        $course = Course::findByTitle($courseName);
        if ($course) {
            $this->jsonResponse($course);
        } else {
            $this->jsonResponse(['error' => 'course not found'], 404);
        }
    }

    // Create course 
    public function createCourse()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = Course::create($data);
            if ($result) {
                $this->jsonResponse(['message' => 'Course created successfully'], 201);
            } else {
                $this->jsonResponse(['error' => 'Failed to create course'], 400);
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    // Update a course
    public function updateCourse($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $updated = Course::update($id, $data);
        if ($updated) {
            $this->jsonResponse(['message' => 'course updated successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to update assigncoursement'], 500);
        }
    }

    // Delete Course
    public function deleteCourse($id)
    {
        $deleted = Course::delete($id);
        if ($deleted) {
            $this->jsonResponse(['message' => 'course deleted successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to delete course'], 500);
        }
    }

    public function assignedUsers($id){
        $users = Course::findAssignedUsers($id);
        if ($users) {
            $this->jsonResponse($users);
        } else {
            $this->jsonResponse(['error' => 'course not found'], 404);
        }
    }
}
