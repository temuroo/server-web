<?php
    // Сохраняем название страницы в переменную.
    $pageTitle = 'Feedback Form';

    // Получаем текущий год для вывода в подвале сайта.
    $year = date('Y');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo-block">
        <img src="logo.png" alt="Логотип Московского Политеха">
    </div>

    <h1><?php echo $pageTitle; ?></h1>
</header>

<main>
    <section class="form-block">
        <h2>Форма обратной связи</h2>

        <form action="https://httpbin.org/post" method="post">
            <label for="name">Имя</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="topic">Тема обращения</label>
            <select id="topic" name="topic" required>
                <option value="">Выберите тему</option>
                <option value="question">Вопрос</option>
                <option value="review">Отзыв</option>
                <option value="problem">Проблема</option>
            </select>

            <label for="message">Сообщение</label>
            <textarea id="message" name="message" rows="6" required></textarea>

            <label class="checkbox-label">
                <input type="checkbox" name="agreement" required>
                Я согласен на обработку данных
            </label>

            <button type="submit">Отправить</button>
        </form>

        <a class="page-link" href="headers.php">Посмотреть get_headers()</a>
    </section>
</main>

<footer>
    <p>Работа выполнена самостоятельно. Лабораторная работа №2. <?php echo $year; ?></p>
</footer>

</body>
</html>