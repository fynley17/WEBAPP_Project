<?php

namespace App\Models;

class Assignment extends Model
{

    public static function all()
    {
        $stmt = self::getDB()->query("
            SELECT 
                users.username,
                courses.cTitle,
                courses.cDate,
                courses.cDuration,
                courses.maxAttendees,
                courses.cDescription,
                COUNT(assignments.userID) AS currentAttendence
            FROM
                assignments
            JOIN
                users ON users.userID = assignments.userID
            JOIN
                courses ON courses.courseID = assignments.courseID
            GROUP BY
                courses.courseID, users.username");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid assignment ID');
        }
        $stmt = self::getDB()->prepare("DELETE FROM assignments WHERE assignmentID = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function findByID($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid assignment ID');
        }

        $stmt = self::getDB()->prepare("
            SELECT
                users.username,
                courses.cTitle,
                courses.cDate,
                courses.cDuration,
                courses.maxAttendees,
                courses.cDescription,
                COUNT(assignments.userID) AS currentAttendence
            FROM
                assignments
            JOIN
                users ON users.userID = assignments.userID
            JOIN
                courses ON courses.courseID = assignments.courseID
            WHERE 
                assignmentID = :id
            GROUP BY
                courses.courseID, users.username");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function findByUsername($username)
    {
        if (!self::validUsername($username)) {
            throw new \InvalidArgumentException('Invalid username format');
        }

        $stmt = self::getDB()->prepare("
            SELECT
                users.username,
                courses.cTitle,
                courses.cDate,
                courses.cDuration,
                courses.maxAttendees,
                courses.cDescription,
                COUNT(assignments.userID) AS currentAttendence
            FROM 
                assignments 
            JOIN
                users ON users.userID = assignments.userID
            JOIN
                courses ON courses.courseID = assignments.courseID 
            WHERE
                users.username = :username");
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        if (empty($data['userID']) || !preg_match("/\d+/", $data['userID'])) {
            throw new \InvalidArgumentException('Invalid userID format');
        }
        if (empty($data['courseID']) || !preg_match("/\d+/", $data['courseID'])) {
            throw new \InvalidArgumentException('invalid courseID format');
        }

        $userID = htmlspecialchars(trim($data['userID']), ENT_QUOTES, 'utf-8');
        $courseID = htmlspecialchars(trim($data['courseID']), ENT_QUOTES, 'utf-8');

        $stmt = self::getDB()->prepare("INSERT INTO `assignments`(`userID`, `courseID`) VALUES (:userID,:userID)");
        $stmt->bindValue(':userID', $userID, \PDO::PARAM_INT);
        $stmt->bindValue(':courseID', $courseID, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function update($id, $data)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid assignment ID');
        }

        $courseID = isset($data['courseID']) ? htmlspecialchars(trim($data['courseID']), ENT_QUOTES, 'utf-8') : null;
        $userID = isset($data['userID']) ? htmlspecialchars(trim($data['userID']), ENT_QUOTES, 'utf-8') : null;

        if ($userID && !preg_match("/\d+/", $data['userID'])) {
            throw new \InvalidArgumentException('invalid userID format');
        }
        if ($courseID && !preg_match("/\d+/", $data['courseID'])) {
            throw new \InvalidArgumentException('invalid courseID format');
        }

        $fields = [];
        $params = ['id' => $id];
        if ($userID) {
            $fields[] = "userID = :userID";
            $params['userID'] = $userID;
        }
        if ($courseID) {
            $fields[] = "courseID = :courseID";
            $params['courseID'] = $courseID;
        }

        if (empty($fields)) {
            throw new \InvalidArgumentException('no valid fields to update');
        }

        $sql = "UPDATE assignments SET " . implode(', ', $fields) . " WHERE assignmentID = :id";
        $stmt = self::getDB()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }


    public static function validUsername($username)
    {
        return preg_match("/^[a-zA-Z0-9_-]{3,30}$/", $username);
    }
}
