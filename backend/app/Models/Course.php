<?php

namespace App\Models;

class Course extends Model
{

    public static function all()
    {
        $stmt = self::getDB()->query("SELECT * FROM courses");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

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
}
