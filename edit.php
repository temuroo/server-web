<?php
// Запрещаем открывать файл напрямую в браузере.
if (!defined('APP_STARTED')) {
  exit('Доступ запрещён.');
}

// Экранируем текст перед выводом в HTML.
function editEscape(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Загружаем записи из JSON-файла.
function editLoadContacts(): array
{
  if (!file_exists(NB_DATA_FILE)) {
    return [];
  }

  $json = file_get_contents(NB_DATA_FILE);
  $contacts = json_decode($json, true);

  return is_array($contacts) ? $contacts : [];
}

// Сохраняем записи обратно в JSON-файл.
function editSaveContacts(array $contacts): bool
{
  return file_put_contents(
    NB_DATA_FILE,
    json_encode(array_values($contacts), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
  ) !== false;
}

// Формируем страницу редактирования записи.
function getEditPage(): string
{
  // Загружаем все контакты.
  $contacts = editLoadContacts();

  $message = '';
  $messageClass = '';

  // Сортируем контакты по фамилии и имени.
  usort($contacts, function ($a, $b) {
    return [$a['last_name'], $a['first_name']] <=> [$b['last_name'], $b['first_name']];
  });

  // Если записей нет, выводим сообщение.
  if (empty($contacts)) {
    return '<h2>Редактирование записи</h2><p class="empty">Записей пока нет.</p>';
  }

  // Получаем id выбранной записи из адресной строки.
  $selectedId = (int)($_GET['id'] ?? $contacts[0]['id']);

  // Если форма отправлена, обновляем запись.
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedId = (int)($_POST['id'] ?? $selectedId);

    // Ищем нужный контакт и заменяем его данные.
    foreach ($contacts as &$contact) {
      if ($contact['id'] === $selectedId) {
        $contact['last_name'] = trim($_POST['last_name'] ?? '');
        $contact['first_name'] = trim($_POST['first_name'] ?? '');
        $contact['middle_name'] = trim($_POST['middle_name'] ?? '');
        $contact['gender'] = trim($_POST['gender'] ?? '');
        $contact['birth_date'] = trim($_POST['birth_date'] ?? '');
        $contact['phone'] = trim($_POST['phone'] ?? '');
        $contact['address'] = trim($_POST['address'] ?? '');
        $contact['email'] = trim($_POST['email'] ?? '');
        $contact['comment'] = trim($_POST['comment'] ?? '');
        break;
      }
    }

    // Удаляем ссылку на последний элемент массива.
    unset($contact);

    // Сохраняем обновленные данные.
    if (editSaveContacts($contacts)) {
      $message = 'Запись обновлена';
      $messageClass = 'success';
    } else {
      $message = 'Ошибка: запись не обновлена';
      $messageClass = 'error';
    }
  }

  // Повторно сортируем контакты после обновления.
  usort($contacts, function ($a, $b) {
    return [$a['last_name'], $a['first_name']] <=> [$b['last_name'], $b['first_name']];
  });

  // По умолчанию выбираем первый контакт.
  $selectedContact = $contacts[0];

  // Ищем выбранный контакт по id.
  foreach ($contacts as $contact) {
    if ($contact['id'] === $selectedId) {
      $selectedContact = $contact;
      break;
    }
  }

  $html = '<h2>Редактирование записи</h2>';

  // Выводим сообщение об успехе или ошибке.
  if ($message !== '') {
    $html .= '<p class="' . $messageClass . '">' . $message . '</p>';
  }

  $html .= '<div class="contact-links">';

  // Создаем ссылки для выбора записи.
  foreach ($contacts as $contact) {
    $activeClass = $contact['id'] === $selectedId ? 'active' : '';

    $html .= '<a class="' . $activeClass . '" href="index.php?page=edit&id=' . $contact['id'] . '">';
    $html .= editEscape($contact['last_name'] . ' ' . $contact['first_name']);
    $html .= '</a>';
  }

  $html .= '</div>';

  // Отмечаем выбранный пол в выпадающем списке.
  $maleSelected = $selectedContact['gender'] === 'Мужской' ? 'selected' : '';
  $femaleSelected = $selectedContact['gender'] === 'Женский' ? 'selected' : '';

  // Формируем форму редактирования.
  $html .= '
    <form method="post" action="index.php?page=edit&id=' . $selectedId . '">
      <input type="hidden" name="id" value="' . $selectedId . '">

      <label>Фамилия</label>
      <input type="text" name="last_name" value="' . editEscape($selectedContact['last_name']) . '" required>

      <label>Имя</label>
      <input type="text" name="first_name" value="' . editEscape($selectedContact['first_name']) . '" required>

      <label>Отчество</label>
      <input type="text" name="middle_name" value="' . editEscape($selectedContact['middle_name']) . '">

      <label>Пол</label>
      <select name="gender">
        <option value="Мужской" ' . $maleSelected . '>Мужской</option>
        <option value="Женский" ' . $femaleSelected . '>Женский</option>
      </select>

      <label>Дата рождения</label>
      <input type="date" name="birth_date" value="' . editEscape($selectedContact['birth_date']) . '" required>

      <label>Телефон</label>
      <input type="text" name="phone" value="' . editEscape($selectedContact['phone']) . '" required>

      <label>Адрес</label>
      <input type="text" name="address" value="' . editEscape($selectedContact['address']) . '">

      <label>E-mail</label>
      <input type="email" name="email" value="' . editEscape($selectedContact['email']) . '" required>

      <label>Комментарий</label>
      <textarea name="comment" rows="4">' . editEscape($selectedContact['comment']) . '</textarea>

      <button type="submit">Сохранить изменения</button>
    </form>
  ';

  return $html;
}