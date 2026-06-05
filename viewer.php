<?php
// Запрещаем открывать файл напрямую в браузере.
if (!defined('APP_STARTED')) {
  exit('Доступ запрещён.');
}

// Экранируем текст перед выводом в HTML.
function viewerEscape(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Загружаем записи из JSON-файла.
function viewerLoadContacts(): array
{
  if (!file_exists(NB_DATA_FILE)) {
    return [];
  }

  $json = file_get_contents(NB_DATA_FILE);
  $contacts = json_decode($json, true);

  return is_array($contacts) ? $contacts : [];
}

// Получаем первую букву строки с поддержкой русского языка.
function viewerFirstLetter(string $text): string
{
  if (preg_match('/^./u', $text, $matches)) {
    return $matches[0];
  }

  return '';
}

// Формируем фамилию и инициалы контакта.
function viewerGetInitials(array $contact): string
{
  $firstInitial = viewerFirstLetter($contact['first_name'] ?? '');
  $middleInitial = viewerFirstLetter($contact['middle_name'] ?? '');

  $text = $contact['last_name'] ?? '';

  if ($firstInitial !== '') {
    $text .= ' ' . $firstInitial . '.';
  }

  if ($middleInitial !== '') {
    $text .= $middleInitial . '.';
  }

  return $text;
}

// Сортируем контакты выбранным способом.
function viewerSortContacts(array $contacts, string $sort): array
{
  usort($contacts, function ($a, $b) use ($sort) {
    if ($sort === 'last_name') {
      return [$a['last_name'], $a['first_name']] <=> [$b['last_name'], $b['first_name']];
    }

    if ($sort === 'birth_date') {
      return [$a['birth_date'], $a['last_name']] <=> [$b['birth_date'], $b['last_name']];
    }

    return ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0);
  });

  return $contacts;
}

// Формируем страницу просмотра записей.
function getViewer(string $sort, int $paginationPage): string
{
  // Загружаем и сортируем контакты.
  $contacts = viewerSortContacts(viewerLoadContacts(), $sort);

  if (empty($contacts)) {
    return '<h2>Просмотр записей</h2><p class="empty">Записей пока нет.</p>';
  }

  // Настраиваем постраничный вывод.
  $perPage = 5;
  $totalPages = max(1, (int)ceil(count($contacts) / $perPage));
  $paginationPage = max(1, min($paginationPage, $totalPages));
  $offset = ($paginationPage - 1) * $perPage;
  $contactsOnPage = array_slice($contacts, $offset, $perPage);

  $html = '<h2>Просмотр записей</h2>';
  $html .= '<div class="contact-list">';

  // Выводим карточки контактов.
  foreach ($contactsOnPage as $contact) {
    $html .= '<article class="contact-card">';
    $html .= '<h3>' . viewerEscape(viewerGetInitials($contact)) . '</h3>';
    $html .= '<p><strong>Пол:</strong> ' . viewerEscape($contact['gender'] ?? '') . '</p>';
    $html .= '<p><strong>Дата рождения:</strong> ' . viewerEscape($contact['birth_date'] ?? '') . '</p>';
    $html .= '<p><strong>Телефон:</strong> ' . viewerEscape($contact['phone'] ?? '') . '</p>';
    $html .= '<p><strong>Адрес:</strong> ' . viewerEscape($contact['address'] ?? '') . '</p>';
    $html .= '<p><strong>E-mail:</strong> ' . viewerEscape($contact['email'] ?? '') . '</p>';
    $html .= '<p><strong>Комментарий:</strong> ' . viewerEscape($contact['comment'] ?? '') . '</p>';
    $html .= '</article>';
  }

  $html .= '</div>';

  // Выводим пагинацию, если страниц больше одной.
  if ($totalPages > 1) {
    $html .= '<div class="pagination">';

    for ($i = 1; $i <= $totalPages; $i++) {
      $activeClass = $i === $paginationPage ? 'active' : '';
      $html .= '<a class="' . $activeClass . '" href="index.php?page=view&sort=' . $sort . '&p=' . $i . '">' . $i . '</a>';
    }

    $html .= '</div>';
  }

  return $html;
}