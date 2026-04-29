<?php
namespace App\Core;

class Config {
    private static array $data = [];

    public static function load(string $path): void {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            self::$data[trim($name)] = trim($value);
            $_ENV[trim($name)] = trim($value);
        }
    }

    public static function get(string $key, $default = null) {
        return self::$data[$key] ?? $default;
    }
}