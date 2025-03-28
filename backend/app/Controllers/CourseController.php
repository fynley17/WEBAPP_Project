<?php

namespace App\Controllers;

use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Retrieve all courses.
     */
    public function getCourses()
    {
        $courses = Course::all();
        $this->jsonResponse($courses);
    }

    /**
     * Retrieve a course by its unique ID.
     * 
     * @param int $id The ID of the course.
     */
    public function getCourseByID($id)
    {
        $course = Course::findByID($id);
        if ($course) {
            $this->jsonResponse($course);
        } else {
            $this->jsonResponse(['error' => 'Course not found'], 404);
        }
    }

    /**
     * Retrieve a course by its title.
     * 
     * @param string $courseName The title of the course.
     */
    public function getCourseByTitle($courseName)
    {
        $course = Course::findByTitle($courseName);
        if ($course) {
            $this->jsonResponse($course);
        } else {
            $this->jsonResponse(['error' => 'Course not found'], 404);
        }
    }

    /**
     * Create a new course.
     */
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

    /**
     * Update an existing course.
     * 
     * @param int $id The ID of the course to update.
     */
    public function updateCourse($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $updated = Course::update($id, $data);
        if ($updated) {
            $this->jsonResponse(['message' => 'Course updated successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to update course'], 500);
        }
    }

    /**
     * Delete a course by its ID.
     * 
     * @param int $id The ID of the course to delete.
     */
    public function deleteCourse($id)
    {
        $deleted = Course::delete($id);
        if ($deleted) {
            $this->jsonResponse(['message' => 'Course deleted successfully']);
        } else {
            $this->jsonResponse(['error' => 'Failed to delete course'], 500);
        }
    }

    /**
     * Retrieve users assigned to a specific course.
     * 
     * @param int $id The ID of the course.
     */
    public function assignedUsers($id)
    {
        $users = Course::findAssignedUsers($id);
        if ($users) {
            $this->jsonResponse($users);
        } else {
            $this->jsonResponse(['error' => 'Course not found'], 404);
        }
    }
}
