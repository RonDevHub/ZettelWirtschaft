<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Variablen aus dem Stack ziehen[cite: 4]
        $host = getenv('DB_HOST') ?: 'db';
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASSWORD');
        $charset = 'utf8mb4';

        // Pflichtfelder prüfen[cite: 4]
        if (!$db || !$user || !$pass) {
            die("Fehler: Datenbank-Konfiguration unvollständig. Prüfe DB_NAME, DB_USER und DB_PASSWORD im Stack.");
        }

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            // Erzwingt TCP-Verbindung statt Socket
            PDO::MYSQL_ATTR_DIRECT_QUERY => true, 
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
            // Tabellen-Initialisierung[cite: 4]
            $this->initialize();
        } catch (PDOException $e) {
            // Präzise Fehlermeldung für das Debugging
            die("Verbindung fehlgeschlagen für User '$user' an Host '$host'. Fehler: " . $e->getMessage());
        }
    }

    private function initialize() {
        // Erstellt alle Tabellen automatisch, falls sie fehlen[cite: 4]
        $queries = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'user') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;",

            "CREATE TABLE IF NOT EXISTS shopping_lists (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;",

            "CREATE TABLE IF NOT EXISTS items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                list_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                quantity VARCHAR(50),
                price DECIMAL(10,2) DEFAULT 0.00,
                deposit DECIMAL(10,2) DEFAULT 0.00,
                is_checked TINYINT(1) DEFAULT 0,
                category VARCHAR(100),
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (list_id) REFERENCES shopping_lists(id) ON DELETE CASCADE
            ) ENGINE=InnoDB;"
        ];

        foreach ($queries as $sql) {
            $this->pdo->exec($sql);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    public function query($sql) {
        return $this->pdo->query($sql);
    }
}