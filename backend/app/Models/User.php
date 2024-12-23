<?php
namespace App\Models;

class User extends Model {

    // Fetch all users
    public static function all() {
        $stmt = self::getDB()->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Fetch user by username
    public static function find($username) {
        // Validation
        if (!preg_match("/^[a-zA-Z0-9_-]{3,30}$/",$username)){
            throw new \InvalidArgumentException('Invalid username format');
        }
        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}