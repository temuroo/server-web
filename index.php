<?php
// Базовые настройки приложения.
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

// Константы проекта.
define('APP_STARTED', true);
define('BASE_PATH', __DIR__);
define('DATA_FILE', BASE_PATH . '/data/notes.json');
define('AUTHOR_NAME', 'Логунов Тимур Андреевич');
define('AUTHOR_GROUP', '251-3210');

// Безопасный вывод текста.
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Нижний регистр для русского текста.
function textLower(string $value): string
{
    return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
}

// Длина текста с поддержкой UTF-8.
function textLength(string $value): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($value);
    }

    preg_match_all('/./us', $value, $matches);
    return count($matches[0]);
}

// Подключение классов.
require_once BASE_PATH . '/src/Router.php';
require_once BASE_PATH . '/src/repositories/NoteRepository.php';
require_once BASE_PATH . '/src/controllers/NoteController.php';

// Создание контроллера и маршрутов.
$controller = new NoteController(new NoteRepository(DATA_FILE));
$router = new Router();

$router->get('/', [$controller, 'home']);
$router->get('/notes', [$controller, 'index']);
$router->get('/notes/add', [$controller, 'add']);
$router->post('/notes/add', [$controller, 'store']);
$router->get('/notes/edit', [$controller, 'edit']);
$router->post('/notes/edit', [$controller, 'update']);
$router->get('/notes/delete', [$controller, 'delete']);
$router->post('/notes/delete', [$controller, 'destroy']);
$router->get('/tips', [$controller, 'tips']);
$router->get('/about', [$controller, 'about']);

// Запуск маршрутизации.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($method, $path);
