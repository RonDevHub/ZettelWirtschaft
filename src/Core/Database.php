<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Diese Werte kommen aus deinem Portainer-Stack (environment)
        $host = 'db'; // Der Name des Services im Stack
        $db   = 'zettelwirtschaft';
        $user = 'ronny';
        $pass = 'db_passwort_456';
        $charset = 'utf8mb4';

        // WICHTIG: host=db erzwingt die Netzwerkverbindung über Docker
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Hier werfen wir den Fehler für das Debugging aus
            die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Proxy-Methoden für bequemen Zugriff
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    public function query($sql) {
        return $this->pdo->query($sql);
    }
}