<?php

namespace App\Models;

use PDO;
use PDOException;

class User extends Model
{
    protected static $table = 'users';

    public $id;
    public $username;
    public $email;
    public $full_name;
    public $password;
    public $phone;
    public $created_at;
    public $updated_at;
    public $role = 'user';

    public static function create(array $data)
    {
        try {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            $sql = "INSERT INTO " . self::$table . " ($columns) VALUES ($placeholders)";
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($data);

            return self::find(self::connect()->lastInsertId());
        } catch (PDOException $e) {
            throw new \Exception("User creation failed: " . $e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            if (isset($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $setClause = '';
            foreach ($data as $key => $value) {
                $setClause .= "$key = :$key, ";
            }
            $setClause = rtrim($setClause, ', ');

            $sql = "UPDATE " . self::$table . " SET $setClause WHERE id = :id";
            $data['id'] = $this->id;

            $stmt = self::connect()->prepare($sql);
            $stmt->execute($data);

            return self::find($this->id);
        } catch (PDOException $e) {
            throw new \Exception("User update failed: " . $e->getMessage());
        }
    }

    public static function find($id)
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE id = :id";
        $stmt = self::connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(__CLASS__);
    }

    public static function findByEmail($email)
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE email = :email";
        $stmt = self::connect()->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchObject(__CLASS__);
    }

    public static function findByUsername($username)
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE username = :username";
        $stmt = self::connect()->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchObject(__CLASS__);
    }

    public static function authenticate($username, $password)
    {
        $user = self::findByUsername($username);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public static function validate(array $data)
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Username is required';
        } elseif (strlen($data['username']) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if (empty($data['full_name'])) {
            $errors[] = 'Full name is required';
        }

        return $errors;
    }
}