<?php
namespace App\Models;

class User extends Model {
    public static function all() {
        $stmt = self::getDB()->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}