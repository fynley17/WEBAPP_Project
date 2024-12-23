<?php
namespace App\Models;

class Course extends Model {
    public static function all() {
        $stmt = self::getDB()->query("SELECT * FROM courses");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}