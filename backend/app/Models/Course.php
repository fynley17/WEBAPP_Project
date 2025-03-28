<?php

namespace App\Models;

class Course extends Model
{
    // Fetch all courses from the database
    public static function all()
    {
        // Query to fetch all courses along with the current attendance count
        $stmt = self::getDB()->query("
            SELECT 
                courses.courseID,
                courses.cTitle,
                courses.cDate,
                courses.cDuration,
                courses.maxAttendees,
                courses.cDescription,
                (SELECT COUNT(DISTINCT assignments.userID) 
                FROM assignments 
                WHERE assignments.courseID = courses.courseID) AS currentAttendence
            FROM
                courses");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Return all courses as an associative array
    }

    // Fetch a single course by its ID
    public static function findByID($id)
    {
        // Validate the course ID
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }

        // Prepare and execute the query to fetch the course
        $stmt = self::getDB()->prepare("SELECT * FROM courses WHERE courseID = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Return the course as an associative array
    }

    // Fetch a course by its title
    public static function findByTitle($courseName)
    {
        // Validate the course title
        if (!self::validTitle($courseName)) {
            throw new \InvalidArgumentException('Invalid course title format');
        }

        // Sanitize the course title
        $username = htmlspecialchars($courseName, ENT_QUOTES, 'UTF-8');

        // Prepare and execute the query to fetch the course
        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Return the course as an associative array
    }

    // Create a new course
    public static function create($data)
    {
        // Validate and sanitize inputs
        if (empty($data['cTitle']) || !preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $data['cTitle'])) {
            throw new \InvalidArgumentException('Invalid title format');
        }
        if (empty($data['cDate']) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['cDate'])) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        if (empty($data['cDuration']) || !preg_match("/^\d+(\.\d*)?$/", $data['cDuration'])) {
            throw new \InvalidArgumentException('Invalid duration format');
        }
        if (empty($data['maxAttendees']) || !preg_match("/^\d{1,3}$/", $data['maxAttendees'])) {
            throw new \InvalidArgumentException('Invalid max attendees format');
        }
        if (empty($data['cDescription']) || !preg_match("/^[a-zA-Z\s'-]{2,1000}$/", $data['cDescription'])) {
            throw new \InvalidArgumentException('Invalid description format');
        }

        // Sanitize input data
        $cTitle = htmlspecialchars(trim($data['cTitle']), ENT_QUOTES, 'UTF-8');
        $cDate = htmlspecialchars(trim($data['cDate']), ENT_QUOTES, 'UTF-8');
        $cDuration = htmlspecialchars(trim($data['cDuration']), ENT_QUOTES, 'UTF-8');
        $maxAttendees = htmlspecialchars(trim($data['maxAttendees']), ENT_QUOTES, 'UTF-8');
        $cDescription = htmlspecialchars(trim($data['cDescription']), ENT_QUOTES, 'UTF-8');

        // Prepare and execute the query to insert the new course
        $stmt = self::getDB()->prepare("INSERT INTO `courses`(`cTitle`, `cDate`, `cDuration`, `maxAttendees`, `cDescription`) VALUES (:cTitle,:cDate,:cDuration,:maxAttendees,:cDescription)");
        $stmt->bindValue(':cTitle', $cTitle, \PDO::PARAM_STR);
        $stmt->bindValue(':cDate', $cDate, \PDO::PARAM_STR);
        $stmt->bindValue(':cDuration', $cDuration, \PDO::PARAM_STR);
        $stmt->bindValue(':maxAttendees', $maxAttendees, \PDO::PARAM_INT);
        $stmt->bindValue(':cDescription', $cDescription, \PDO::PARAM_STR);
        return $stmt->execute(); // Return true if the query was successful
    }

    // Update an existing course
    public static function update($id, $data)
    {
        // Validate the course ID
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }

        // Sanitize and validate input fields
        $cTitle = isset($data['cTitle']) ? htmlspecialchars(trim($data['cTitle']), ENT_QUOTES, 'UTF-8') : null;
        $cDate = isset($data['cDate']) ? htmlspecialchars(trim($data['cDate']), ENT_QUOTES, 'UTF-8') : null;
        $cDuration = isset($data['cDuration']) ? htmlspecialchars(trim($data['cDuration']), ENT_QUOTES, 'UTF-8') : null;
        $maxAttendees = isset($data['maxAttendees']) ? htmlspecialchars(trim($data['maxAttendees']), ENT_QUOTES, 'UTF-8') : null;
        $currentAttendees = isset($data['currentAttendees']) ? htmlspecialchars(trim($data['currentAttendees']), ENT_QUOTES, 'UTF-8') : null;
        $cDescription = isset($data['cDescription']) ? htmlspecialchars(trim($data['cDescription']), ENT_QUOTES, 'UTF-8') : null;

        // Validate each field if provided
        if ($cTitle && !preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $data['cTitle'])) {
            throw new \InvalidArgumentException('Invalid title format');
        }
        if ($cDate && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['cDate'])) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        if ($cDuration && !preg_match("/^\d+(\.\d*)?$/", $data['cDuration'])) {
            throw new \InvalidArgumentException('Invalid duration format');
        }
        if ($maxAttendees && !preg_match("/^\d{1,3}$/", $data['maxAttendees'])) {
            throw new \InvalidArgumentException('Invalid max attendees format');
        }
        if ($currentAttendees && !preg_match("/^\d{1,3}$/", $data['currentAttendees'])) {
            throw new \InvalidArgumentException('Invalid current attendees format');
        }
        if ($cDescription && !preg_match("/^[a-zA-Z\s'-]{2,1000}$/", $data['cDescription'])) {
            throw new \InvalidArgumentException('Invalid description format');
        }

        // Build the SQL query dynamically based on provided fields
        $fields = [];
        $params = ['id' => $id];
        if ($cTitle) {
            $fields[] = "cTitle = :cTitle";
            $params['cTitle'] = $cTitle;
        }
        if ($cDate) {
            $fields[] = "cDate = :cDate";
            $params['cDate'] = $cDate;
        }
        if ($cDuration) {
            $fields[] = "cDuration = :cDuration";
            $params['cDuration'] = $cDuration;
        }
        if ($maxAttendees) {
            $fields[] = "maxAttendees = :maxAttendees";
            $params['maxAttendees'] = $maxAttendees;
        }
        if ($currentAttendees) {
            $fields[] = "currentAttendence = :currentAttendees";
            $params['currentAttendees'] = $currentAttendees;
        }
        if ($cDescription) {
            $fields[] = "cDescription = :cDescription";
            $params['cDescription'] = $cDescription;
        }

        // Ensure at least one field is being updated
        if (empty($fields)) {
            throw new \InvalidArgumentException('No valid fields to update');
        }

        // Prepare and execute the update query
        $sql = "UPDATE courses SET " . implode(', ', $fields) . " WHERE courseID = :id";
        $stmt = self::getDB()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute(); // Return true if the query was successful
    }

    // Delete a course by its ID
    public static function delete($id)
    {
        // Validate the course ID
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }

        // Prepare and execute the delete query
        $stmt = self::getDB()->prepare("DELETE FROM courses WHERE courseID = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute(); // Return true if the query was successful
    }

    // Find all users assigned to a specific course
    public static function findAssignedUsers($id)
    {
        // Validate the course ID
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }

        // Prepare and execute the query to fetch assigned users
        $stmt = self::getDB()->prepare("
            SELECT users.userID, users.username, assignmentID
            FROM `assignments` 
            JOIN users ON users.userID = assignments.userID 
            WHERE courseID = :id;
        ");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Fetch all assigned users

        return $users ?: null; // Return users or null if none are found
    }

    // Helper function to validate course titles
    public static function validTitle($title)
    {
        // Sanitize input
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        return preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $title); // Validate title format
    }
}
