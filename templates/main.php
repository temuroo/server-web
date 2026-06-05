<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Мой блог</title>
  <link rel="stylesheet" href="/styles/style.css">
</head>
<body>

<table class="layout">
  <tr>
    <td colspan="2" class="header">
      Мой блог
    </td>
  </tr>

  <tr>
    <td>
      <?= $content ?>
    </td>

    <td width="300px" class="sidebar">
      <div class="sidebarHeader">Меню</div>

      <ul>
        <li><a href="/">Главная страница</a></li>
        <li><a href="/about-me">Обо мне</a></li>
        <li><a href="/hello/Тимур">Приветствие</a></li>
        <li><a href="/bye/Тимур">Прощание</a></li>
      </ul>
    </td>
  </tr>

  <tr>
    <td class="footer" colspan="2">
      Все права защищены (c) Мой блог
    </td>
  </tr>
</table>

</body>
</html>