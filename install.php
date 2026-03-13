<?php
/**
 * Один раз: создаёт БД, таблицы и учётку админа (admingross / admingross).
 * После установки удалите или переименуйте этот файл.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение — те же данные, что в config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'u3429662_default');
define('DB_PASS', 'Ng4fU7I3FAlbr1yl');
define('DB_NAME', 'u3429662_test_system');
define('DB_CHARSET', 'utf8mb4');

$ok = true;
$messages = [];

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");

    $schema = file_get_contents(__DIR__ . '/sql/schema.sql');
    $pdo->exec($schema);
    $messages[] = 'Таблицы созданы.';

    $st = $pdo->prepare('SELECT id FROM users WHERE login = ?');
    $st->execute(['admingross']);
    if (!$st->fetch()) {
        $hash = password_hash('admingross', PASSWORD_DEFAULT);
        $pdo->prepare('INSERT INTO users (login, password_hash, first_name, last_name, birth_date, is_admin) VALUES (?,?,?,?,?,1)')
            ->execute(['admingross', $hash, 'Admin', 'Gross', '1990-01-01']);
        $messages[] = 'Админ создан: логин admingross, пароль admingross.';
    } else {
        $messages[] = 'Админ уже существует.';
    }
} catch (Throwable $e) {
    $ok = false;
    $msg = $e->getMessage();
    $messages[] = 'Ошибка: ' . $msg;
    if (stripos($msg, 'could not find driver') !== false || stripos($msg, 'pdo_mysql') !== false) {
        $messages[] = 'Включи драйвер PDO MySQL в PHP (см. ниже).';
    }
}

header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Установка</title></head><body style="font-family:sans-serif;padding:2rem; max-width:640px;">';
echo '<h1>Волки — установка</h1>';
foreach ($messages as $m) {
    echo '<p>' . htmlspecialchars($m) . '</p>';
}
if (!$ok && isset($msg) && (stripos($msg, 'could not find driver') !== false || stripos($msg, 'pdo_mysql') !== false)) {
    echo '<hr><p><strong>Как включить драйвер MySQL в PHP</strong></p>';
    echo '<ol style="line-height:1.6;">';
    echo '<li>Открой папку <code>C:\php</code> (или где у тебя установлен PHP).</li>';
    echo '<li>Найди файл <code>php.ini</code>. Если его нет — скопируй <code>php.ini-development</code> и переименуй в <code>php.ini</code>.</li>';
    echo '<li>Открой <code>php.ini</code> в блокноте. Найди строку:<br><code>;extension=pdo_mysql</code></li>';
    echo '<li>Убери точку с запятой в начале, чтобы стало:<br><code>extension=pdo_mysql</code></li>';
    echo '<li>Сохрани файл и перезапусти start.bat (или веб‑сервер).</li>';
    echo '</ol>';
    echo '<p>Если строки нет — добавь в конец файла новую строку: <code>extension=pdo_mysql</code></p>';
}
if ($ok) {
    echo '<p><strong>Готово.</strong> Удалите или переименуйте <code>install.php</code> и откройте сайт.</p>';
}
echo '</body></html>';
