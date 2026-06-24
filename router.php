<?php
// Роутер для встроенного сервера PHP.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// CSS и другие файлы отдаются напрямую.
if (is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
