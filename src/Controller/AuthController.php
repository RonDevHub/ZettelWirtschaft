<?php
namespace App\Controller;

use App\Core\Database;

class AuthController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: /dashboard");
                exit;
            }
            $error = "Ungültige Anmeldedaten";
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
                
                // Ersten User zum Admin machen, alle anderen sind User
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
        // Hier lag der Fehler: Pfad mit __DIR__ absichern
        include __DIR__ . '/../Views/auth/register.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }
}