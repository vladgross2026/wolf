<?php
/**
 * Применение миграций БД. Запустить один раз после обновления кода.
 * В браузере: http://ваш-сайт/migrate.php
 * Или из консоли: php migrate.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');
$isCli = (php_sapi_name() === 'cli');

try {
    $pdo = Db::get();
    $pdo->exec("CREATE TABLE IF NOT EXISTS `migrations` (
        `version` varchar(64) NOT NULL PRIMARY KEY,
        `applied_at` datetime DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $migrationsDir = __DIR__ . '/sql/migrations';
    if (!is_dir($migrationsDir)) {
        echo $isCli ? "Папка migrations не найдена.\n" : '<p>Папка migrations не найдена.</p>';
        exit(0);
    }

    $files = glob($migrationsDir . '/*.sql');
    sort($files);
    $applied = [];
    $errors = [];

    foreach ($files as $path) {
        $version = basename($path, '.sql');
        $st = $pdo->prepare('SELECT 1 FROM migrations WHERE version = ?');
        $st->execute([$version]);
        if ($st->fetch()) continue;

        $sql = file_get_contents($path);
        $sql = preg_replace('/--.*$/m', '', $sql);
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $stmt) {
            if ($stmt === '') continue;
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate column') !== false || strpos($e->getMessage(), 'already exists') !== false) {
                    continue;
                }
                $errors[] = $version . ': ' . $e->getMessage();
            }
        }

        $pdo->prepare('INSERT INTO migrations (version) VALUES (?)')->execute([$version]);
        $applied[] = $version;
    }

    if ($isCli) {
        foreach ($applied as $v) echo "Применено: $v\n";
        foreach ($errors as $e) echo "Ошибка: $e\n";
        if (empty($applied) && empty($errors)) echo "Новых миграций нет.\n";
    } else {
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Миграции</title></head><body style="font-family:sans-serif;padding:2rem;">';
        echo '<h1>Миграции БД</h1>';
        if (!empty($applied)) {
            echo '<p><strong>Применено:</strong> ' . implode(', ', $applied) . '</p>';
        }
        if (!empty($errors)) {
            echo '<p style="color:red;"><strong>Ошибки:</strong><br>' . nl2br(htmlspecialchars(implode("\n", $errors))) . '</p>';
        }
        if (empty($applied) && empty($errors)) echo '<p>Новых миграций нет.</p>';
        echo '<p><a href="' . (BASE_PATH ?: '/') . '">На главную</a></p></body></html>';
    }
} catch (Throwable $e) {
    if ($isCli) {
        echo 'Ошибка: ' . $e->getMessage() . "\n";
    } else {
        echo '<p style="color:red;">Ошибка: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    exit(1);
}
