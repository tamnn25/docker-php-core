<?php

namespace Model\User;

use Model\Database\Database;
use PDOException;

class user
{
    public static function getUser()
    {
        $db = Database::getDB();
        try {
            $query = "SELECT * FROM users";
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo "Database error: $error_message";
            exit();
        }
    }

    public static function store($user_name, $email, $password)
    {
        $db = Database::getDB();
        try {
            $query = "INSERT INTO users (user_name, email, password) 
                        VALUES (:user_name, :email, :password)";

            $statement = $db->prepare($query);
            $statement->bindValue(':user_name', $user_name);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->execute();
            $statement->closeCursor();
        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            echo "Database error: " . $error_message;
            exit();
        }
    }

    public static function checkLogIn($email, $password)
    {
        $db = Database::getDB();
        try {
            $query = "SELECT * FROM users where email=:email and password=:password";
            $statement = $db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        } catch (\Throwable $th) {
            $error_message = $th->getMessage();
            echo "Database error: " . $error_message;
            exit();
        }
    }

    public static function delete($id)
    {
        $db = Database::getDB();
        try {
            $query = "DELETE FROM users WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id);
            $statement->execute();
            $statement->closeCursor();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public static function show($id)
    {
        $db = Database::getDB();
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindValue(':id', $id);
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        } catch (\Throwable $th) {
            echo 'loi roi' . $th->getMessage();
        }
    }

    public static function update($id, $userName, $email, $password)
    {
        $db = Database::getDB();
        try {
            $query = "UPDATE users 
                            SET user_name=:user_name, 
                            email=:email, 
                            password=:password 
                                     WHERE id=:id";
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':user_name', $userName);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $password);
            $statement->execute();
            $statement->closeCursor();
        } catch (\Throwable $th) {
            echo  'update loi' . $th->getMessage();
        }
    }
}
