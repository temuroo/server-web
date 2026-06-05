<?php
// Запрещаем открывать файл напрямую в браузере.
if (!defined('APP_STARTED')) {
  exit('Доступ запрещён.');
}

// Формируем главное меню и подменю сортировки.
function getMenu(): string
{
  // Список разрешенных страниц.
  $allowedPages = ['view', 'add', 'edit', 'delete'];
  $page = $_GET['page'] ?? 'view';

  // Если страница указана неверно, используем просмотр.
  if (!in_array($page, $allowedPages)) {
    $page = 'view';
  }

  // Список разрешенных типов сортировки.
  $allowedSorts = ['created', 'last_name', 'birth_date'];
  $sort = $_GET['sort'] ?? 'created';

  // Если сортировка указана неверно, используем сортировку по добавлению.
  if (!in_array($sort, $allowedSorts)) {
    $sort = 'created';
  }

  // Пункты главного меню.
  $mainItems = [
    'view' => 'Просмотр',
    'add' => 'Добавление записи',
    'edit' => 'Редактирование записи',
    'delete' => 'Удаление записи',
  ];

  $html = '<nav class="menu">';

  // Создаем ссылки главного меню.
  foreach ($mainItems as $key => $title) {
    $activeClass = $page === $key ? 'active' : '';
    $html .= '<a class="' . $activeClass . '" href="index.php?page=' . $key . '">' . $title . '</a>';
  }

  $html .= '</nav>';

  // Подменю сортировки показываем только на странице просмотра.
  if ($page === 'view') {
    $sortItems = [
      'created' => 'По добавлению',
      'last_name' => 'По фамилии',
      'birth_date' => 'По дате рождения',
    ];

    $html .= '<nav class="submenu">';

    // Создаем ссылки для сортировки.
    foreach ($sortItems as $key => $title) {
      $activeClass = $sort === $key ? 'active' : '';
      $html .= '<a class="' . $activeClass . '" href="index.php?page=view&sort=' . $key . '">' . $title . '</a>';
    }

    $html .= '</nav>';
  }

  return $html;
}