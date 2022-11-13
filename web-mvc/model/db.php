<?php

namespace Model\Database;

use PDO;
use PDOException;

class Database
{
    private static $dsn = 'mysql:host=db;dbname=user';
    private static $username = 'user';
    private static $password = 'pass';
    private static $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    private static $db;
    public function __construct()
    {
    }
    public static function getDB()
    {
        if (!isset(self::$db)) {
            try {
                self::$db = new PDO(
                    self::$dsn,
                    self::$username,
                    self::$password,
                    self::$options
                );
                // echo "data connect successfully";
            } catch (PDOException $e) {

                $error_message = $e->getMessage();
                echo "Error Connecting to databasse: " . $error_message;
            }
        }

        return self::$db;
    }
}
