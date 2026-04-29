<?php
namespace App\Controller;

use App\Core\Database;
use App\Controller\AuthController;

class AdminController {
    public function __construct() {
        AuthController::check();
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit;
        }
    }

    public function index() {
        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM categories ORDER BY default_order ASC")->fetchAll();
        $masterProducts = $db->query("SELECT p.*, c.name as cat_name FROM products_master p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.name ASC")->fetchAll();
        
        include __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $order = (int)$_POST['order'];
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO categories (name, default_order) VALUES (?, ?)");
            $stmt->execute([$name, $order]);
        }
        header("Location: /admin");
    }

    public function deleteCategory() {
        $id = (int)$_GET['id'];
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: /admin");
    }
}