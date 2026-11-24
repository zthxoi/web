<?php
declare(strict_types=1);

spl_autoload_register();

use MVC\Controllers\Controller;

header('Content-Type: text/plain; charset=utf-8');

$obj = new Controller('users.markdown');
echo $obj->render();