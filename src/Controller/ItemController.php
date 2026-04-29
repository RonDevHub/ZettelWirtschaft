<?php
namespace App\Controller;

use App\Core\Database;
use App\Core\Config;
use App\Core\Calculator;

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

            $this->notifyMercure($listId, ['action' => 'list_updated']);
            header("Location: /list/view?id=" . $listId);
            exit;
        }
    }

    public function updatePrice() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['item_id'];
            $formula = $_POST['price_formula'] ?? '0';
            $listId = (int)$_POST['list_id'];
            
            $totalPrice = Calculator::calculate($formula);

            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE list_items SET price_formula = ?, total_price = ?, is_checked = 1, checked_at = NOW() WHERE id = ?");
            $stmt->execute([$formula, $totalPrice, $id]);

            $this->notifyMercure($listId, ['action' => 'list_updated']);
            header("Location: /list/view?id=" . $listId);
            exit;
        }
    }

    public function addDeposit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $listId = (int)$_POST['list_id'];
            $amount = Calculator::calculate($_POST['deposit_amount']);
            
            $db = Database::getInstance();
            // Pfand wird als spezielles Item mit negativem Preis gespeichert
            $stmt = $db->prepare("INSERT INTO list_items (list_id, name, total_price, is_checked, checked_at) VALUES (?, 'Pfandbon', ?, 1, NOW())");
            $stmt->execute([$listId, -$amount]);

            $this->notifyMercure($listId, ['action' => 'list_updated']);
            header("Location: /list/view?id=" . $listId);
            exit;
        }
    }

    public function toggle() {
        $id = (int)$_GET['id'];
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT is_checked, list_id FROM list_items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if ($item) {
            $newStatus = $item['is_checked'] ? 0 : 1;
            $stmt = $db->prepare("UPDATE list_items SET is_checked = ?, checked_at = ? WHERE id = ?");
            $stmt->execute([$newStatus, $newStatus ? date('Y-m-d H:i:s') : null, $id]);
            $this->notifyMercure((int)$item['list_id'], ['action' => 'list_updated']);
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