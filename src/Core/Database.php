<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = getenv('DB_HOST') ?: 'db';
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASSWORD');

        if (!$db || !$user || !$pass) {
            die("Fehler: Datenbank-Konfiguration unvollständig.");
        }

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        
        try {
            // Wir setzen die Optionen direkt im Konstruktor
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            $this->initialize();[cite: 4]
        } catch (PDOException $e) {
            // Das ist der Fehler, den du siehst.
            die("Verbindung fehlgeschlagen für User '$user' an Host '$host'. Fehler: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        // Falls die Verbindung verloren ging oder nicht existiert, neu aufbauen
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initialize() {
        // Tabellen erstellen[cite: 4]
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");
        
        // ... (restliche Tabellen wie shopping_lists und items)
    }

    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    public function query($sql) {
        return $this->pdo->query($sql);
    }
}