<?php

// 1. Более элегантная проверка версии
if (PHP_VERSION_ID < 80100) {
    fprintf(STDERR, "Error: PHP 8.1 or above is required to run Doctum.\n");
    fprintf(STDERR, "Current version: %s (ID: %d)\n", PHP_VERSION, PHP_VERSION_ID);
    exit(1);
}

// 2. Определение пути к автозагрузчику Composer
// Используем оператор ?? (null-coalescing) для чистоты
$autoload = getenv('DOCTUM_COMPOSER_AUTOLOAD_FILE') ?: null;

if (!$autoload) {
    $paths = [
        __DIR__ . '/../../../autoload.php', // Если установлено как зависимость
        __DIR__ . '/../vendor/autoload.php',   // Если запуск из корня проекта
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            $autoload = $path;
            break;
        }
    }
}

// 3. Финальная проверка и запуск
if (!$autoload || !file_exists($autoload)) {
    fprintf(STDERR, "Error: Composer autoload file not found. Run 'composer install'.\n");
    exit(1);
}

require_once $autoload;

(new Doctum\Console\Application())->run();
