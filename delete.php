<?php
// Запрещаем открывать файл напрямую в браузере.
if (!defined('APP_STARTED')) {
  exit('Доступ запрещён.');
}

// Экранируем текст перед выводом в HTML.
function deleteEscape(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Загружаем записи из JSON-файла.
function deleteLoadContacts(): array
{
  if (!file_exists(NB_DATA_FILE)) {
    return [];
  }

  $json = file_get_contents(NB_DATA_FILE);
  $contacts = json_decode($json, true);

  return is_array($contacts) ? $contacts : [];
}

// Сохраняем записи обратно в JSON-файл.
function deleteSaveContacts(array $contacts): bool
{
  return file_put_contents(
    NB_DATA_FILE,
    json_encode(array_values($contacts), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
  ) !== false;
}

// Получаем первую букву строки с поддержкой русского языка.
function deleteFirstLetter(string $text): string
{
  if (preg_match('/^./u', $text, $matches)) {
    return $matches[0];
  }

  return '';
}

// Формируем фамилию и инициалы контакта.
function deleteGetInitials(array $contact): string
{
  $firstInitial = deleteFirstLetter($contact['first_name']);
  $middleInitial = deleteFirstLetter($contact['middle_name']);

  $text = $contact['last_name'];

  if ($firstInitial !== '') {
    $text .= ' ' . $firstInitial . '.';
  }

  if ($middleInitial !== '') {
    $text .= $middleInitial . '.';
  }

  return $text;
}

// Формируем страницу удаления записи.
function getDeletePage(): string
{
  $contacts = deleteLoadContacts();
  $message = '';

  // Сортируем записи по фамилии и имени.
  usort($contacts, function ($a, $b) {
    return [$a['last_name'], $a['first_name']] <=> [$b['last_name'], $b['first_name']];
  });

  // Получаем id записи для удаления из адресной строки.
  $deleteId = (int)($_GET['id'] ?? 0);

  if ($deleteId > 0) {
    $deletedLastName = '';

    // Ищем запись по id и удаляем ее из массива.
    foreach ($contacts as $index => $contact) {
      if ($contact['id'] === $deleteId) {
        $deletedLastName = $contact['last_name'];
        unset($contacts[$index]);
        break;
      }
    }

    // Если запись найдена, сохраняем новый список.
    if ($deletedLastName !== '') {
      $contacts = array_values($contacts);
      deleteSaveContacts($contacts);
      $message = 'Запись с фамилией ' . deleteEscape($deletedLastName) . ' удалена';

      usort($contacts, function ($a, $b) {
        return [$a['last_name'], $a['first_name']] <=> [$b['last_name'], $b['first_name']];
      });
    }
  }

  $html = '<h2>Удаление записи</h2>';

  if ($message !== '') {
    $html .= '<p class="success">' . $message . '</p>';
  }

  if (empty($contacts)) {
    return $html . '<p class="empty">Записей пока нет.</p>';
  }

  $html .= '<p class="center-text">Выберите запись для удаления:</p>';
  $html .= '<div class="contact-links">';

  // Выводим ссылки на удаление записей.
  foreach ($contacts as $contact) {
    $html .= '<a href="index.php?page=delete&id=' . $contact['id'] . '">';
    $html .= deleteEscape(deleteGetInitials($contact));
    $html .= '</a>';
  }

  $html .= '</div>';

  return $html;
}