<?php
namespace App\Controller;

use App\Core\Database;
use App\Controller\AuthController;

class ListController {
    public function __construct() {
        AuthController::check();
    }

    public function index() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM shopping_lists WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $lists = $stmt->fetchAll();

        // Absoluter Pfad ausgehend vom aktuellen Verzeichnis des Controllers
        include __DIR__ . '/../Views/lists/index.php';
    }
}