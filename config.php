<?php
/**
 * Конфигурация — Тайный факультет Волки
 * Настройте под свой PHPMyAdmin / MySQL.
 */
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'u3429662_test_system');
define('DB_USER', 'u3429662_default');
define('DB_PASS', 'Ng4fU7I3FAlbr1yl');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'Волки');
// Если сайт в подпапке (например http://localhost/secret-faculty-wolves/), раскомментируйте и укажите путь:
// define('BASE_PATH', '/secret-faculty-wolves');
if (!defined('BASE_PATH')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($scriptDir === '/' || $scriptDir === '' || $scriptDir === '.') {
        define('BASE_PATH', '');
    } else {
        $parent = dirname($scriptDir);
        define('BASE_PATH', ($parent === '/' || $parent === '.' || $parent === '') ? '' : rtrim($parent, '/'));
    }
}
define('BASE_URL', 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . BASE_PATH);

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/includes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
