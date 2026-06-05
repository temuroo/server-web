<?php
// Показываем ошибки для отладки.
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Подключаем контроллер.
require_once __DIR__ . '/controllers/MainController.php';

// Загружаем маршруты.
$routes = require __DIR__ . '/routes.php';

// Получаем путь из URL.
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = rawurldecode($requestPath);

$content = '';

// Ищем подходящий маршрут.
foreach ($routes as $pattern => $route) {
  if (preg_match($pattern, $requestPath, $matches)) {
    $controllerName = $route['controller'];
    $actionName = $route['action'];

    $controller = new $controllerName();

    // Убираем первое совпадение, оставляем параметры.
    array_shift($matches);

    $content = $controller->$actionName(...$matches);

    break;
  }
}

// Если маршрут не найден.
if ($content === '') {
  http_response_code(404);

  $content = '
    <h2>Ошибка 404</h2>
    <p>Страница не найдена.</p>
  ';
}

// Подключаем общий шаблон.
require __DIR__ . '/templates/main.php';