<?php
declare(strict_types=1);

require_once __DIR__ . '/NewsDB.class.php';

$news = new NewsDB();
$errMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/save_news.inc.php';
}

if (isset($_GET['del'])) {
    require __DIR__ . '/delete_news.inc.php';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новостная лента (SPL)</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 0 15px; line-height: 1.5; }
        .error { color: #b00020; font-weight: bold; }
        .news-item { margin: 15px 0; }
        .meta { color: #555; font-size: 0.9em; }
        .delete-link { color: #b00020; }
        input[type="text"], select, textarea { width: 100%; max-width: 500px; box-sizing: border-box; margin: 4px 0 10px; padding: 6px; }
        .uml-block img { max-width: 100%; height: auto; border: 1px solid #ddd; padding: 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Последние новости</h1>
    <?php
    if ($errMsg !== '') {
        echo '<p class="error">' . htmlspecialchars($errMsg) . '</p>';
    }
    ?>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        Заголовок новости:<br>
        <input type="text" name="title"><br>
        Выберите категорию:<br>
        <select name="category">
            <?php foreach ($news as $id => $name): ?>
                <option value="<?= (int) $id ?>"><?= htmlspecialchars((string) $name) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        Текст новости:<br>
        <textarea name="description" cols="50" rows="5"></textarea><br>
        Источник:<br>
        <input type="text" name="source"><br>
        <br>
        <input type="submit" value="Добавить!">
    </form>
    <hr>
    <?php
    require __DIR__ . '/get_news.inc.php';
    ?>
    <section class="uml-block">
        <h2>Диаграмма классов</h2>
        <img src="news_spl.png" alt="Диаграмма классов новостной ленты с SPL">
    </section>
</body>
</html>
