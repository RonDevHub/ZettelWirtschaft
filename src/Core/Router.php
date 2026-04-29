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
    ];

    public function run() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if (isset($this->routes[$path])) {
            list($controllerName, $method) = $this->routes[$path];
            $controller = new $controllerName();
            $controller->$method();
        } else {
            http_response_code(404);
            echo "404 - Seite nicht gefunden.";
        }
    }
}