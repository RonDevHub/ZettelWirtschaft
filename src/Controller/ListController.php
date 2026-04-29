<?php
namespace App\Controller;

use App\Core\Database;

class ListController {
    public function __construct() {
        AuthController::check();
    }

    public function index() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_lists WHERE created_by = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $lists = $stmt->fetchAll();
        include __DIR__ . '/../Views/dashboard.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? 'Neue Liste');
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO shopping_lists (name, created_by) VALUES (?, ?)");
            $stmt->execute([$name, $_SESSION['user_id']]);
            header("Location: /dashboard");
            exit;
        }
    }

    public function view() {
        $id = (int)$_GET['id'];
        $db = Database::getInstance();

        // Liste holen
        $stmt = $db->prepare("SELECT * FROM shopping_lists WHERE id = ?");
        $stmt->execute([$id]);
        $list = $stmt->fetch();

        // Alle Kategorien für das Modal
        $catStmt = $db->query("SELECT * FROM categories ORDER BY default_order ASC");
        $allCategories = $catStmt->fetchAll();

        // Items nach Kategorien gruppiert holen
        $itemStmt = $db->prepare("
            SELECT i.*, c.name as category_name 
            FROM list_items i 
            LEFT JOIN categories c ON i.category_id = c.id 
            WHERE i.list_id = ? 
            ORDER BY c.default_order ASC, i.created_at DESC
        ");
        $itemStmt->execute([$id]);
        $items = $itemStmt->fetchAll();

        $categories = [];
        foreach ($items as $item) {
            $categories[$item['category_id']]['name'] = $item['category_name'] ?? 'Unsortiert';
            $categories[$item['category_id']]['items'][] = $item;
        }

        include __DIR__ . '/../Views/list/view.php';
    }
}