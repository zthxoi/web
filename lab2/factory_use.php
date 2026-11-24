<?php
declare(strict_types=1);

spl_autoload_register();

use Factory\Router;

$obj = Router::parse('users');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №2  Factory Method</title>
</head>
<body>
    <h1>Шаблон Фабричный метод</h1>
    <div>
        <?= $obj->render() ?>
    </div>
</body>
</html>