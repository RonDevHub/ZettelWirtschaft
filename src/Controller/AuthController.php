<?php
namespace App\Controller;

use App\Core\Database;
use App\Core\Config;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header("Location: /dashboard");
                exit;
            }
            $error = "Ungültige Anmeldedaten.";
        }
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (strlen($username) < 3 || strlen($password) < 8) {
                $error = "Nutzername min. 3, Passwort min. 8 Zeichen.";
            } else {
                $db = Database::getInstance();
                $hash = password_hash($password, PASSWORD_ARGON2ID);
                try {
                    $stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
                    $stmt->execute([$username, $hash]);
                    header("Location: /login");
                    exit;
                } catch (\PDOException $e) {
                    $error = "Nutzername bereits vergeben.";
                }
            }
        }
        include __DIR__ . '/../Views/auth/register.php';
    }

    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }
}