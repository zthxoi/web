<?php
declare(strict_types=1);

$title = $news->clearStr((string) ($_POST['title'] ?? ''));
$category = $news->clearInt($_POST['category'] ?? 0);
$description = $news->clearStr((string) ($_POST['description'] ?? ''));
$source = $news->clearStr((string) ($_POST['source'] ?? ''));

if ($title === '' || $category === 0 || $description === '' || $source === '') {
    $errMsg = 'Заполните все поля формы!';
} else {
    if ($news->saveNews($title, $category, $description, $source)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $errMsg = 'Произошла ошибка при добавлении новости';
    }
}
