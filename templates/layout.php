<?php
// Общий макет сайта.
if (!defined('APP_STARTED')) {
    exit('Доступ запрещён.');
}

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Заметки') ?></title>
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>
<header class="site-header">
    <div class="header-content">
        <a class="brand" href="/">
            <span class="brand-icon">N</span>
            <span>
                <strong>NotesApp</strong>
                <small>Курсовой проект</small>
            </span>
        </a>
    </div>

    <nav class="main-menu">
        <a class="<?= $currentPath === '/' ? 'active' : '' ?>" href="/">Главная</a>
        <a class="<?= $currentPath === '/notes' ? 'active' : '' ?>" href="/notes">Заметки</a>
        <a class="<?= $currentPath === '/notes/add' ? 'active' : '' ?>" href="/notes/add">Добавить</a>
        <a class="<?= $currentPath === '/tips' ? 'active' : '' ?>" href="/tips">Советы</a>
        <a class="<?= $currentPath === '/about' ? 'active' : '' ?>" href="/about">О проекте</a>
    </nav>
</header>

<main class="container">
    <?= $content ?? '' ?>
</main>

<footer class="site-footer">
    <p>Курсовой проект по серверной веб-разработке</p>
    <p><?= e(AUTHOR_NAME) ?> • группа <?= e(AUTHOR_GROUP) ?></p>
</footer>
</body>
</html>
