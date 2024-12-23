<?php
namespace App\Models;

class Assignment extends Model {
    public static function all() {
        $stmt = self::getDB()->query("SELECT * FROM assignments");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}