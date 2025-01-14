<?php

namespace App\Models;

class Assignment extends Model
{

    public static function all()
    {
        $stmt = self::getDB()->query("SELECT assignmentID,users.username,courses.cTitle,courses.cDate,courses.cDuration,courses.currentAttendence FROM assignments JOIN users ON users.userID = assignments.userID JOIN courses ON courses.courseID = assignments.courseID");
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

        $stmt = self::getDB()->prepare("SELECT assignmentID,users.username,courses.cTitle,courses.cDate,courses.cDuration,courses.currentAttendence FROM assignments JOIN users ON users.userID = assignments.userID JOIN courses ON courses.courseID = assignments.courseID WHERE assignmentID = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function findByUsername($username)
    {
        if (!self::validUsername($username)) {
            throw new \InvalidArgumentException('Invalid username format');
        }


        $stmt = self::getDB()->prepare("SELECT assignmentID,users.username,courses.cTitle,courses.cDate,courses.cDuration,courses.currentAttendence FROM assignments JOIN users ON users.userID = assignments.userID JOIN courses ON courses.courseID = assignments.courseID WHERE users.username = :username");
        $stmt->bindValue(':username', $username, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data){
        if (empty($data['userID']) || !is_numeric($data['userID'])){
            throw new \InvalidArgumentException('Invalid userID format');
        }
        if (empty($data['courseID']) || !is_numeric($data['courseID'])){
            throw new \InvalidArgumentException('invalid courseID format');
        }

        $userID = htmlspecialchars(trim($data['userID']), ENT_QUOTES, 'utf-8');
        $courseID = htmlspecialchars(trim($data['courseID']), ENT_QUOTES, 'utf-8');

        $stmt = self::getDB()->prepare("INSERT INTO `assignments`(`userID`, `courseID`) VALUES (:userID,:userID)");
        $stmt->bindvalue(':userID',$userID, \PDO::PARAM_INT);
        $stmt->bindvalue(':courseID',$courseID, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function validUsername($username)
    {
        return preg_match("/^[a-zA-Z0-9_-]{3,30}$/", $username);
    }
}
