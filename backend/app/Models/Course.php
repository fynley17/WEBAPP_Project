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

    public static function create($data)
    {
        // Validate and sanitize inputs
        if (empty($data['cTitle']) || !preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $data['cTitle'])) {
            throw new \InvalidArgumentException(' Invalid title format');
        }
        if (empty($data['cDate']) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['cDate'])) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        if (empty($data['cDuration']) || !preg_match("/^\d+(\.\d*)?$/", $data['cDuration'])) {
            throw new \InvalidArgumentException('invalid duration format');
        }
        if (empty($data['maxAttendees']) || !preg_match("/^\d{1,3}$/", $data['maxAttendees'])) {
            throw new \InvalidArgumentException('invaid max attende format');
        }
        if (empty($data['cDescription']) || !preg_match("/^[a-zA-Z\s'-]{2,1000}$/", $data['cDescription'])) {
            throw new \InvalidArgumentException('invalid description format');
        }

        $cTitle = htmlspecialchars(trim($data['cTitle']), ENT_QUOTES, 'UTF-8');
        $cDate = htmlspecialchars(trim($data['cDate']), ENT_QUOTES, 'UTF-8');
        $cDuration = htmlspecialchars(trim($data['cDuration']), ENT_QUOTES, 'UTF-8');
        $maxAttendees = htmlspecialchars(trim($data['maxAttendees']), ENT_QUOTES, 'UTF-8');
        $cDescription = htmlspecialchars(trim($data['cDescription']), ENT_QUOTES, 'UTF-8');

        $stmt = self::getDB()->prepare("INSERT INTO `courses`(`cTitle`, `cDate`, `cDuration`, `maxAttendees`, `cDescription`) VALUES (:cTitle,:cDate,:cDuration,:maxAttendees,:cDescription)");
        $stmt->bindvalue(':cTitle', $cTitle, \PDO::PARAM_STR);
        $stmt->bindvalue(':cDate', $cDate, \PDO::PARAM_STR);
        $stmt->bindvalue(':cDuration', $cDuration, \PDO::PARAM_STR);
        $stmt->bindvalue(':maxAttendees', $maxAttendees, \PDO::PARAM_INT);
        $stmt->bindvalue(':cDescription', $cDescription, \PDO::PARAM_STR); // Fixed typo here
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }

        $cTitle = isset($data['cTitle']) ? htmlspecialchars(trim($data['cTitle']), ENT_QUOTES, 'UTF-8') : null;
        $cDate = isset($data['cDate']) ? htmlspecialchars(trim($data['cDate']), ENT_QUOTES, 'UTF-8') : null;
        $cDuration = isset($data['cDuration']) ? htmlspecialchars(trim($data['cDuration']), ENT_QUOTES, 'UTF-8') : null;
        $maxAttendees = isset($data['maxAttendees']) ? htmlspecialchars(trim($data['maxAttendees']), ENT_QUOTES, 'UTF-8') : null;
        $currentAttendees = isset($data['currentAttendees']) ? htmlspecialchars(trim($data['currentAttendees']), ENT_QUOTES, 'UTF-8') : null;
        $cDescription = isset($data['cDescription']) ? htmlspecialchars(trim($data['cDescription']), ENT_QUOTES, 'UTF-8') : null;


        if ($cTitle && !preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $data['cTitle'])) {
            throw new \InvalidArgumentException(' Invalid title format');
        }
        if ($cDate && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['cDate'])) {
            throw new \InvalidArgumentException('Invalid date format');
        }
        if ($cDuration && !preg_match("/^\d+(\.\d*)?$/", $data['cDuration'])) {
            throw new \InvalidArgumentException('invalid duration format');
        }
        if ($maxAttendees && !preg_match("/^\d{1,3}$/", $data['maxAttendees'])) {
            throw new \InvalidArgumentException('invaid max attende format');
        }
        if ($currentAttendees && !preg_match("/^\d{1,3}$/", $data['currentAttendees'])) {
            throw new \InvalidArgumentException('invaid current attende format');
        }
        if ($cDescription && !preg_match("/^[a-zA-Z\s'-]{2,1000}$/", $data['cDescription'])) {
            throw new \InvalidArgumentException('invalid description format');
        }

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

        if (empty($fields)) {
            throw new \InvalidArgumentException('No valid fields to update');
        }

        $sql = "UPDATE courses SET " . implode(', ', $fields) . " WHERE courseID = :id";
        $stmt = self::getDB()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }

    public static function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid course ID');
        }
        $stmt = self::getDB()->prepare("DELETE FROM courses WHERE courseID = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Healpers
    public static function validTitle($title)
    {
        // Sanitize input
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        return preg_match("/^[a-zA-Z0-9\s.,!?\'-]{1,100}$/", $title);
    }
}
