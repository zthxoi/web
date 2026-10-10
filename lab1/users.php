<?php
declare(strict_types=1);
use MyProject\Classes\User;
use MyProject\Classes\SuperUser;
spl_autoload_register(function ($className) {
    $className = str_replace('MyProject\\Classes\\', '', $className);
    $file = __DIR__ . '/Classes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
$user1 = new User('Иван', 'ivan_92', 'qwerty123');
$user2 = new User('Петр', 'petr_pro', 'pass456');
$user3 = new User('Анна', 'anna_star', 'secret789');

$user1->showInfo();
$user2->showInfo();
$user3->showInfo();

$user = new SuperUser('Главный Админ', 'admin_root', 'superpass123', 'Администратор');
$user->showInfo();
echo "<h3>Данные из метода getInfo():</h3>";
echo "<pre>";
print_r($user->getInfo());
echo "<pre>";
echo "<hr>";
echo "<b>Всего обычных пользователей:</b> " . User::$userCount . "<br>";
echo "<b>Всего супер-пользователей:</b> " . SuperUser::$superUserCount . "<br>";