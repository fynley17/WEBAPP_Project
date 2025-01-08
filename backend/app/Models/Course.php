<?php

namespace App\Models;

class Course extends Model
{

    // Fetch all courses
    public static function all()
    {
        $stmt = self::getDB()->query("SELECT * FROM courses");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Fetch course by id
    public static function findByID($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }

        $stmt = self::getDB()->prepare("SELECT * FROM courses WHERE courseID = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Fetch course by title
    public static function findByTitle($courseName)
    {
        // Validation
        if (!self::validTitle($courseName)) {
            throw new \InvalidArgumentException('Invalid username format');
        }
        $username = htmlspecialchars($courseName, ENT_QUOTES, 'UTF-8');
        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create() {}

    public static function update() {}

    public static function delete() {}

    // Healpers
    public static function validTitle($title)
    {
        // Assuming $pdo is your PDO connection
        $title = trim($_POST['title']); // Get input and trim whitespace

        // Sanitize input
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        return preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $title);
    }
}
