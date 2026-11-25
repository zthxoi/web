<?php
declare(strict_types=1);

$posts = $news->getNews();

if ($posts === false) {
    $errMsg = 'Произошла ошибка при выводе новостной ленты';
    echo '<p class="error">' . htmlspecialchars($errMsg) . '</p>';
} else {
    echo '<p><strong>Всего новостей:</strong> ' . count($posts) . '</p>';

    foreach ($posts as $item) {
        $id = (int) $item['id'];
        $title = htmlspecialchars((string) $item['title']);
        $category = htmlspecialchars((string) $item['category']);
        $description = nl2br(htmlspecialchars((string) $item['description']));
        $source = htmlspecialchars((string) $item['source']);
        $dt = date('d.m.Y H:i:s', (int) $item['datetime']);

        echo <<<HTML
        <article class="news-item" id="news-{$id}">
            <h3><a href="#news-{$id}">{$title}</a></h3>
            <p class="meta">Категория: <strong>{$category}</strong> | Дата: {$dt} | Источник: {$source}</p>
            <p>{$description}</p>
            <p><a class="delete-link" href="{$_SERVER['PHP_SELF']}?del={$id}">Удалить</a></p>
        </article>
        <hr>
HTML;
    }
}
