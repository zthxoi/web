<?php
declare(strict_types=1);

spl_autoload_register();

use Singleton\Settings;

Settings::get()->items_per_page = 20;
Settings::get()->site_name = 'Учебный портал';
Settings::get()->debug_mode = true;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №2 — Singleton</title>
</head>
<body>
    <h1>Шаблон Одиночка (Singleton)</h1>
    <ul>
        <li><strong>Число (items_per_page):</strong> <?= Settings::get()->items_per_page ?></li>
        <li><strong>Строка (site_name):</strong> <?= htmlspecialchars((string) Settings::get()->site_name) ?></li>
        <li><strong>Логическое (debug_mode):</strong> <?= Settings::get()->debug_mode ? 'true' : 'false' ?></li>
    </ul>
</body>
</html>