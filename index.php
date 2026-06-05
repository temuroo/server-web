<?php
// Разрешаем подключенным файлам работать только через главный файл index.php.
define('APP_STARTED', true);

// Указываем путь к JSON-файлу, где хранятся записи записной книжки.
define('NB_DATA_FILE', __DIR__ . '/contacts.json');

// Подключаем файлы с меню, просмотром, добавлением, редактированием и удалением записей.
require_once __DIR__ . '/menu.php';
require_once __DIR__ . '/viewer.php';
require_once __DIR__ . '/add.php';
require_once __DIR__ . '/edit.php';
require_once __DIR__ . '/delete.php';

// Получаем текущую страницу из адресной строки.
$page = $_GET['page'] ?? 'view';

// Список разрешенных страниц.
$allowedPages = ['view', 'add', 'edit', 'delete'];

// Если пользователь передал неправильную страницу, открываем просмотр.
if (!in_array($page, $allowedPages)) {
  $page = 'view';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Notebook</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo-block">
    <img src="img/logo.png" alt="Логотип Московского Политеха">
  </div>

  <h1>Notebook</h1>
</header>

<main>
  <section class="card">
    <?= getMenu() ?>

    <?php
    // Показываем нужный раздел приложения.
    if ($page === 'add') {
      echo getAddPage();
    } elseif ($page === 'edit') {
      echo getEditPage();
    } elseif ($page === 'delete') {
      echo getDeletePage();
    } else {
      // Получаем сортировку и номер страницы для просмотра записей.
      $sort = $_GET['sort'] ?? 'created';
      $paginationPage = (int)($_GET['p'] ?? 1);

      echo getViewer($sort, $paginationPage);
    }
    ?>
  </section>
</main>

<footer>
  <p>Задание выполнено самостоятельно. Лабораторная работа №5.</p>
</footer>

</body>
</html>