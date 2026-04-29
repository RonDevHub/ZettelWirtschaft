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
        '/item/update-price' => ['App\Controller\ItemController', 'updatePrice'], // NEU
        '/item/add-deposit' => ['App\Controller\ItemController', 'addDeposit']    // NEU
        '/admin' => ['App\Controller\AdminController', 'index'], // NEU
        '/admin/category/add' => ['App\Controller\AdminController', 'addCategory'], // NEU
        '/admin/category/delete' => ['App\Controller\AdminController', 'deleteCategory'], // NEU
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
}