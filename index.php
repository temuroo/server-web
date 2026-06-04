<?php
  // Устанавливаем московский часовой пояс для работы с датой и временем.
  date_default_timezone_set('Europe/Moscow');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hello, World!</title>

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: #0b0b0f;
      color: #ffffff;
      display: flex;
      flex-direction: column;
    }

    header {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      padding: 22px 40px;
      background: #111118;
      border-bottom: 2px solid #2f6fff;
    }

    .header-left img {
      width: 260px;
      max-width: 100%;
      height: auto;
      display: block;
    }

    header h1 {
      margin: 0;
      font-size: 1.6rem;
      text-align: center;
      color: #ffffff;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 30px;
    }

    .card {
      width: 100%;
      max-width: 520px;
      padding: 45px;
      text-align: center;
      background: #161620;
      border: 1px solid #2b2b38;
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    .card h2 {
      margin-top: 0;
      font-size: 2rem;
    }

    .card p {
      color: #b8b8c7;
    }

    .time-display {
      margin: 15px 0;
      font-size: 3.5rem;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: 2px;
    }

    .date {
      color: #9ea7ff;
    }

    hr {
      border: 0;
      border-top: 1px solid #333344;
      margin: 30px 0;
    }

    .greeting {
      font-size: 1.2rem;
      color: #ffffff;
    }

    footer {
      padding: 25px;
      text-align: center;
      background: #111118;
      color: #9a9aaa;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 3px;
      border-top: 1px solid #2b2b38;
    }
  </style>
</head>

<body>

<header>
  <div class="header-left">
    <img src="img/logo.png" alt="Логотип Московского Политеха">
  </div>

  <h1>Домашняя работа: Hello, World!</h1>

  <div class="header-right"></div>
</header>

<main>
  <div class="card">
    <h2>Привет, мир!</h2>

    <p>Московское время на сервере:</p>

    <div class="time-display">
      <?php
        // Выводим текущее время сервера в формате часы:минуты:секунды.
        echo date("H:i:s");
      ?>
    </div>

    <p class="date">
      <?php
        // Выводим текущую дату сервера в формате день.месяц.год.
        echo date("d.m.Y");
      ?>
    </p>

    <hr>

    <div class="greeting">
      <?php
        // Получаем текущий час и превращаем его в число.
        $hour = (int)date("H");

        // По текущему часу выбираем подходящее приветствие.
        if ($hour >= 5 && $hour < 12) {
          echo "🌅 Доброе утро!";
        } elseif ($hour >= 12 && $hour < 18) {
          echo "🏙️ Добрый день!";
        } elseif ($hour >= 18 && $hour < 23) {
          echo "🌆 Добрый вечер!";
        } else {
          echo "🌌 Доброй ночи!";
        }
      ?>
    </div>
  </div>
</main>

<footer>
  Задание для самостоятельной работы
</footer>

</body>
</html>