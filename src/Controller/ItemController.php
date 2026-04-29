<?php
namespace App\Controller;

use App\Core\Database;
use App\Core\Config;

class ItemController {
    public function __construct() {
        AuthController::check();
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $listId = (int)$_POST['list_id'];
            $name = trim($_POST['name']);
            $categoryId = (int)$_POST['category_id'];
            $amount = trim($_POST['amount'] ?? '');

            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO list_items (list_id, category_id, name, amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([$listId, $categoryId, $name, $amount]);

            $this->notifyMercure($listId, ['action' => 'item_added']);
            header("Location: /list/view?id=" . $listId);
            exit;
        }
    }

    public function toggle() {
        $id = (int)$_GET['id'];
        $db = Database::getInstance();
        
        // Aktuellen Status holen
        $stmt = $db->prepare("SELECT is_checked, list_id FROM list_items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if ($item) {
            $newStatus = $item['is_checked'] ? 0 : 1;
            $checkedAt = $newStatus ? date('Y-m-d H:i:s') : null;
            
            $update = $db->prepare("UPDATE list_items SET is_checked = ?, checked_at = ? WHERE id = ?");
            $update->execute([$newStatus, $checkedAt, $id]);

            $this->notifyMercure((int)$item['list_id'], [
                'action' => 'item_toggled',
                'item_id' => $id,
                'status' => $newStatus
            ]);
        }
        
        if (isset($_GET['ajax'])) {
            echo json_encode(['success' => true]);
            exit;
        }
        header("Location: /list/view?id=" . $item['list_id']);
    }

    private function notifyMercure(int $listId, array $data) {
        $url = Config::get('MERCURE_PUBLIC_URL');
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                            "Authorization: Bearer " . Config::get('MERCURE_JWT_KEY') . "\r\n",
                'content' => http_build_query([
                    'topic' => "http://zettelwirtschaft.local/list/{$listId}",
                    'data' => json_encode($data)
                ])
            ]
        ]);
        @file_get_contents($url, false, $context);
    }
}