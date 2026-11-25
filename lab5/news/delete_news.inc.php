<?php
declare(strict_types=1);

$id = $news->clearInt($_GET['del'] ?? 0);

if ($id === 0) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (!$news->deleteNews($id)) {
    $errMsg = 'Произошла ошибка при удалении новости';
} else {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
