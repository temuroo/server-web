<?php
// Защита от прямого открытия.
if (!defined('APP_STARTED')) {
    exit('Доступ запрещён.');
}

// Простой роутер проекта.
class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    // GET-маршрут.
    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    // POST-маршрут.
    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    // Поиск и запуск маршрута.
    public function dispatch(string $method, string $path): void
    {
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler !== null) {
            call_user_func($handler);
            return;
        }

        http_response_code(404);
        $title = 'Страница не найдена';
        $content = '<section class="card center"><h1>Ошибка 404</h1><p class="muted">Такой страницы нет.</p><a class="button" href="/">На главную</a></section>';
        require BASE_PATH . '/templates/layout.php';
    }
}
