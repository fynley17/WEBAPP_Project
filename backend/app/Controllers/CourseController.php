<?php
namespace App\Controllers;

use App\Models\Course;

class CourseController extends Controller {
    public function getCourses(){
        $courses = Course::all();
        $this->jsonResponse($courses);
    }
}