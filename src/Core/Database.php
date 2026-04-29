<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    Config::get('DB_HOST'),
                    Config::get('DB_NAME')
                );
                self::$instance = new PDO($dsn, Config::get('DB_USER'), Config::get('DB_PASSWORD'), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Datenbankfehler: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}