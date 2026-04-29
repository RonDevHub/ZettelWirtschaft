<?php
namespace App\Controller;

use App\Core\Database;

class AuthController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                // Nutzt die zentrale Instanz, die wir gerade fixiert haben
                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    header("Location: /dashboard");
                    exit;
                }
                $error = "Ungültige Anmeldedaten";
            } catch (\Exception $e) {
                // Falls hier der 1045 Fehler fliegt, liegt es an der Database-Klasse
                $error = "Datenbankfehler: " . $e->getMessage();
            }
        }
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!empty($username) && !empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $db = Database::getInstance();
                
                // Ersten User zum Admin machen
                $stmt = $db->query("SELECT COUNT(*) FROM users");
                $count = $stmt->fetchColumn();
                $role = ($count == 0) ? 'admin' : 'user';

                try {
                    $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $hash, $role]);
                    header("Location: /login?registered=1");
                    exit;
                } catch (\PDOException $e) {
                    $error = "Benutzername bereits vergeben";
                }
            } else {
                $error = "Bitte alle Felder ausfüllen";
            }
        }
        include __DIR__ . '/../Views/auth/register.php';
    }
}