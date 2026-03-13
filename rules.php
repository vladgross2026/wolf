<?php
require_once __DIR__ . '/config.php';

$pdo = Db::get();
$page = null;
try {
    $st = $pdo->query("SELECT id, title, body, updated_at FROM pages WHERE slug = 'rules' LIMIT 1");
    $page = $st->fetch();
} catch (Throwable $e) { }

$pageTitle = $page ? $page['title'] : 'Правила';
require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <?php if ($page && trim($page['body']) !== ''): ?>
        <h2><?= htmlspecialchars($page['title']) ?></h2>
        <?php if (!empty($page['updated_at'])): ?>
            <p style="font-size:0.9rem; color:var(--text-muted); margin:0 0 1rem;">Обновлено: <?= $page['updated_at'] ?></p>
        <?php endif; ?>
        <div style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($page['body'])) ?></div>
    <?php else: ?>
        <h2>Правила</h2>
        <p style="color:var(--text-muted);">Содержимое страницы пока не добавлено. Администратор может заполнить его в разделе «Правила» админ-панели.</p>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php';
