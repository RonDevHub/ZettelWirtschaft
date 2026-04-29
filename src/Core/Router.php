<?php
namespace App\Core;

class Router {
    private array $routes = [
        '/' => ['App\Controller\AuthController', 'login'],
        '/login' => ['App\Controller\AuthController', 'login'],
        '/register' => ['App\Controller\AuthController', 'register'],
        '/logout' => ['App\Controller\AuthController', 'logout'],
        '/dashboard' => ['App\Controller\ListController', 'index'],
        '/list/create' => ['App\Controller\ListController', 'create'],
        '/list/view' => ['App\Controller\ListController', 'view'],
        '/item/add' => ['App\Controller\ItemController', 'add'],
        '/item/toggle' => ['App\Controller\ItemController', 'toggle'],
        '/item/update-price' => ['App\Controller\ItemController', 'updatePrice'],
        '/item/add-deposit' => ['App\Controller\ItemController', 'addDeposit'], // Hier fehlte das Komma!
        '/admin' => ['App\Controller\AdminController', 'index'],
        '/admin/category/add' => ['App\Controller\AdminController', 'addCategory'],
        '/admin/category/delete' => ['App\Controller\AdminController', 'deleteCategory'],
    ];

    public function run() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (isset($this->routes[$path])) {
            list($class, $method) = $this->routes[$path];
            (new $class())->$method();
        } else {
            http_response_code(404);
            echo "404 - Seite nicht gefunden.";
        }
    }

    // Falls die index.php die Methode 'dispatch' statt 'run' aufruft:
    public function dispatch() {
        $this->run();
    }
}