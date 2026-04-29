<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../src/Core/Autoloader.php';

// Einfaches Autoloading für den Start
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\Router;
use App\Core\Config;

// Konfiguration laden
Config::load(__DIR__ . '/../.env');

// Routing Logik (sehr simpel für den Anfang)
$router = new Router();
$router->run();