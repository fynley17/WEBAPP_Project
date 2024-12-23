<?php
namespace App\Models;

class User extends Model {

    // Fetch all users
    public static function all() {
        $stmt = self::getDB()->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Fetch user by id
    public static function findByID($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid user ID');
        }

        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Fetch user by username
    public static function findByUsername($username) {
        // Validation
        if (!self::validUsername($username)){
            throw new \InvalidArgumentException('Invalid username format');
        }
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $stmt = self::getDB()->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Delete user
    public static function delete($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid user ID');
        }
        $stmt = self::getDB()->prepare("DELETE FROM users WHERE userID = :id");
        $stmt->bindValue(':id',$id,\PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function validUsername($username){
        return preg_match("/^[a-zA-Z0-9_-]{3,30}$/",$username);
    }
}