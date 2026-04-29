<?php
namespace App\Controller;

use App\Core\Database;
use App\Controller\AuthController;

class ListController {
    public function __construct() {
        // Hier wurde die undefinierte Methode gerufen
        AuthController::check();
    }

    public function index() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_lists WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $lists = $stmt->fetchAll();

        include __DIR__ . '/../Views/lists/index.php';
    }
}