<?php
namespace App\Core;

class Autoloader {
    public static function register() {
        spl_autoload_register(function ($class) {
            // Namespace-Prefix (App\)
            $prefix = 'App\\';
            $base_dir = __DIR__ . '/../';

            // Prüfen, ob die Klasse den Prefix nutzt
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            // Relativen Klassennamen bestimmen
            $relative_class = substr($class, $len);

            // Datei-Pfad bauen: Namespace-Backslashes zu Slashes machen
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // Wenn die Datei existiert, einbinden
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}