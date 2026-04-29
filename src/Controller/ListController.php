<?php
namespace App\Controller;

use App\Core\Database;

class ListController {
    public function __construct() {
        AuthController::check();
    }

    public function index() {
        $db = Database::getInstance();
        // Listen des Users und geteilte Listen abrufen
        $stmt = $db->prepare("
            SELECT sl.* FROM shopping_lists sl 
            WHERE sl.created_by = ? 
            OR sl.id IN (SELECT list_id FROM user_lists WHERE user_id = ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
        $lists = $stmt->fetchAll();
        
        include __DIR__ . '/../Views/dashboard.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? 'Neue Liste');
            $token = bin2hex(random_bytes(16));
            
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO shopping_lists (name, share_token, created_by) VALUES (?, ?, ?)");
            $stmt->execute([$name, $token, $_SESSION['user_id']]);
            
            header("Location: /dashboard");
            exit;
        }
    }
}