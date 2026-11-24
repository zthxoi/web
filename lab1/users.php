<?php
declare(strict_types=1);

use MyProject\Classes\User;
use MyProject\Classes\SuperUser;

spl_autoload_register(function (string $className): void {
    $file = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$user1 = new User('Иван', 'ivan_92', 'qwerty123');
$user2 = new User('Петр', 'petr_pro', 'pass456');
$user3 = new User('Анна', 'anna_star', 'secret789');

$user = new SuperUser('Главный Админ', 'admin_root', 'superpass123', 'Администратор');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №1 — Классы и интерфейсы</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 0 15px; line-height: 1.5; }
        .card { border: 1px solid #ddd; border-radius: 6px; padding: 10px 15px; margin-bottom: 12px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Лабораторная работа №1: Классы и интерфейсы</h1>

    <h2>Обычные пользователи</h2>
    <div class="card"><?php $user1->showInfo(); ?></div>
    <div class="card"><?php $user2->showInfo(); ?></div>
    <div class="card"><?php $user3->showInfo(); ?></div>

    <h2>Супер-пользователь</h2>
    <div class="card"><?php $user->showInfo(); ?></div>

    <h3>Данные из метода getInfo():</h3>
    <pre><?php print_r($user->getInfo()); ?></pre>

    <hr>
    <p><strong>Всего обычных пользователей:</strong> <?= User::$userCount ?></p>
    <p><strong>Всего супер-пользователей:</strong> <?= SuperUser::$superUserCount ?></p>

    <hr>
    <h3>Удаление объектов</h3>
    <?php unset($user1, $user2, $user3, $user); ?>
</body>
</html>
