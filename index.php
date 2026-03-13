<?php
require_once __DIR__ . '/config.php';

if (Auth::user()) {
    header('Location: ' . BASE_PATH . '/cabinet/');
    exit;
}

$pageTitle = '';
$extraCss = '<link rel="stylesheet" href="' . BASE_PATH . '/assets/css/landing.css">';
require __DIR__ . '/includes/header.php';
?>
<div class="landing">
    <div class="landing-bg"></div>

    <section class="hero">
        <div class="hero-badge">Тайный факультет</div>
        <h1 class="hero-title">Волки</h1>
        <p class="hero-lead">Единое пространство для участников: рейтинг, звания, достижения и история каждого.</p>
        <div class="hero-actions">
            <a href="<?= BASE_PATH ?>/auth/register.php" class="btn btn-hero">Присоединиться</a>
            <a href="<?= BASE_PATH ?>/auth/login.php" class="btn btn-hero-ghost">Войти</a>
        </div>
    </section>

    <section class="brand-features">
        <h2 class="brand-features-title">Всё в одном месте</h2>
        <p class="brand-features-lead">Платформа, которая объединяет участников и отражает вклад каждого.</p>
        <div class="brand-features-grid">
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">◆</div>
                <h3 class="feature-title">Рейтинг и звания</h3>
                <p class="feature-desc">Прозрачная система уровней и опыта. Видно, кто чем отличился и как растёт рейтинг.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">★</div>
                <h3 class="feature-title">Достижения</h3>
                <p class="feature-desc">Награды за дела и события. В профиле — полная картина заслуг участника.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">◈</div>
                <h3 class="feature-title">История опыта</h3>
                <p class="feature-desc">Каждое начисление опыта фиксируется с комментарием. Всё открыто и понятно.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon" aria-hidden="true">◇</div>
                <h3 class="feature-title">Лицо факультета</h3>
                <p class="feature-desc">Общий стиль, единый бренд. Сайт — визитка сообщества для всех.</p>
            </article>
        </div>
    </section>

    <section class="brand-cta">
        <h2 class="brand-cta-title">Готовы стать частью?</h2>
        <p class="brand-cta-lead">Зарегистрируйтесь и откройте доступ к рейтингу, кабинету и достижениям.</p>
        <a href="<?= BASE_PATH ?>/auth/register.php" class="btn btn-cta">Зарегистрироваться</a>
    </section>
</div>
<?php require __DIR__ . '/includes/footer.php';
