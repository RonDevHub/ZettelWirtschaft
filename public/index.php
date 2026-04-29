<?php
// Fehleranzeige für Debug-Phase
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Den Autoloader einbinden und registrieren
require_once __DIR__ . '/../src/Core/Autoloader.php';
\App\Core\Autoloader::register();

use App\Core\Router;

// Router initialisieren
$router = new Router();

// Anfrage verarbeiten
// Die Routen sind bereits in der Router-Klasse definiert
$router->dispatch();