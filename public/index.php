<?php
// Fehleranzeige für Debug-Phase (In Prod später ausblenden)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Den Autoloader einbinden und registrieren
require_once __DIR__ . '/../src/Core/Autoloader.php';
\App\Core\Autoloader::register();

use App\Core\Router;

// Router initialisieren
$router = new Router();

// Routen definieren (Beispielhaft)
$router->add('GET', '/', ['App\Controller\AuthController', 'showLogin']);
$router->add('POST', '/login', ['App\Controller\AuthController', 'login']);
$router->add('GET', '/dashboard', ['App\Controller\ListController', 'index']);

// Anfrage verarbeiten
$router->dispatch();