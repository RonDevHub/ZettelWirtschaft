<?php
namespace App\Controller;

use App\Core\Database;

class AuthController {
    
    // Diese Methode hat gefehlt!
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
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
                $error = "Datenbankfehler: " . $e->getMessage();
            }
        }
        include __DIR__ . '/../Views/auth/login.php';
    }

    // ... Rest (register, logout) bleibt gleich
}