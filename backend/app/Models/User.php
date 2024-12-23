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

    // Create a new user
    public static function create($data) {
        // Validate and sanitize input
        if (empty($data['email']) || !self::ValidEmail($data['email'])) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        if (empty($data['username']) || !self::ValidUsername($data['username'])) {
            throw new \InvalidArgumentException('Invalid username format');
        }
        if (empty($data['password']) || strlen($data['password']) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters long');
        }
        if (empty($data['firstName']) || !self::names($data['firstName'])) {
            throw new \InvalidArgumentException('not valid first name');
        }
        if (empty($data['lastName']) || !self::names($data['lastName'])) {
            throw new \InvalidArgumentException('not valid last name');
        }
        if (empty($data['jobTitle']) ||!preg_match("/^[a-zA-Z\s'-]{2,100}$/", $data['jobTitle'])) {
            throw new \InvalidArgumentException('Invalid job title format');
        }
        if (empty($data['accessLevel']) || ($data['accessLevel'] != "staff" && $data['accessLevel'] != "admin")) {
            throw new \InvalidArgumentException('not valid access level');
        }        


        $username = htmlspecialchars(trim($data['username']), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars(trim($data['email']), ENT_QUOTES, 'UTF-8');
        $password = password_hash(trim($data['password']), PASSWORD_BCRYPT);
        $firstname = htmlspecialchars($data['firstName'], ENT_QUOTES, 'UTF-8');
        $lastname = htmlspecialchars($data['lastName'], ENT_QUOTES, 'UTF-8');
        $job_title = htmlspecialchars($data['jobTitle'], ENT_QUOTES, 'UTF-8');
        $access_level = htmlspecialchars($data['accessLevel'], ENT_QUOTES, 'UTF-8');


        // Insert into database
        $stmt = self::getDB()->prepare("INSERT INTO users (email, username, password,firstName,lastName,jobTitle,accessLevel) VALUES (:email, :username, :password,:firstname,:lastname,:job_title,:access_level)");
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, \PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $stmt->bindValue(':job_title', $job_title, \PDO::PARAM_STR);
        $stmt->bindValue(':access_level', $access_level, \PDO::PARAM_STR);
        return $stmt->execute();
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


    // Helpers
    public static function names($name){
        return preg_match("/^[a-zA-Z\s'-]{2,50}$/", $name);
    }

    public static function validUsername($username){
        return preg_match("/^[a-zA-Z0-9_-]{3,30}$/",$username);
    }

    private static function ValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}