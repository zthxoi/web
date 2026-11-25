<?php
declare(strict_types=1);

require_once __DIR__ . '/INewsDB.class.php';

/**
 * Класс для работы с новостной лентой на базе SQLite3 с поддержкой SPL и генерацией RSS.
 */
class NewsDB implements INewsDB, IteratorAggregate
{
    /** Имя файла базы данных */
    public const DB_NAME = 'news.db';

    /** Имя RSS-файла */
    public const RSS_NAME = 'rss.xml';

    /** Заголовок RSS-ленты */
    public const RSS_TITLE = 'Последние новости';

    /** Ссылка на новостную ленту */
    public const RSS_LINK = 'http://zthxoi1.xsph.ru/lab5/news/news.php';

    /** @var SQLite3|null Экземпляр подключения к SQLite3 */
    private ?SQLite3 $_db = null;

    /** @var array<int, string> Список категорий новостей (id => name) */
    private array $items = [];

    /**
     * Конструктор: открывает БД (или создаёт её) и загружает список категорий.
     */
    public function __construct()
    {
        $dbPath = __DIR__ . '/' . self::DB_NAME;
        $isNew = !file_exists($dbPath) || filesize($dbPath) === 0;

        $this->_db = new SQLite3($dbPath);

        if ($isNew) {
            $sqlCreateMsgs = "
                CREATE TABLE msgs(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT,
                    category INTEGER,
                    description TEXT,
                    source TEXT,
                    datetime INTEGER
                )
            ";

            $sqlCreateCategory = "
                CREATE TABLE category(
                    id INTEGER,
                    name TEXT
                )
            ";

            $sqlInsertCategory = "
                INSERT INTO category(id, name)
                SELECT 1 as id, 'Политика' as name
                UNION SELECT 2 as id, 'Культура' as name
                UNION SELECT 3 as id, 'Спорт' as name
            ";

            $this->_db->exec($sqlCreateMsgs);
            $this->_db->exec($sqlCreateCategory);
            $this->_db->exec($sqlInsertCategory);
        }

        $this->getCategories();
    }

    /**
     * Деструктор: закрывает соединение и удаляет экземпляр SQLite3.
     */
    public function __destruct()
    {
        if ($this->_db !== null) {
            $this->_db->close();
            unset($this->_db);
        }
    }

    /**
     * Загружает категории из таблицы category в свойство $items.
     *
     * @return void
     */
    private function getCategories(): void
    {
        $sql = "SELECT id, name FROM category";
        $result = $this->_db->query($sql);

        if ($result !== false) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $this->items[(int) $row['id']] = (string) $row['name'];
            }
        }
    }

    /**
     * Возвращает итератор по списку категорий новостей.
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Обеспечивает классам-наследникам доступ на чтение к свойству $_db.
     *
     * @return SQLite3|null
     */
    protected function getDb(): ?SQLite3
    {
        return $this->_db;
    }

    /**
     * Очищает строковые данные.
     *
     * @param string $data
     * @return string
     */
    public function clearStr(string $data): string
    {
        return trim(strip_tags($data));
    }

    /**
     * Приводит значение к неотрицательному целому числу.
     *
     * @param mixed $data
     * @return int
     */
    public function clearInt($data): int
    {
        return abs((int) $data);
    }

    /**
     * Добавление новой записи в новостную ленту и обновление RSS.
     *
     * @param string $title Заголовок новости
     * @param int|string $category Категория новости
     * @param string $description Текст новости
     * @param string $source Источник новости
     * @return bool
     */
    public function saveNews($title, $category, $description, $source): bool
    {
        $dt = time();
        $sql = "INSERT INTO msgs (title, category, description, source, datetime)
                VALUES (:title, :category, :description, :source, :datetime)";

        $stmt = $this->_db->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $stmt->bindValue(':title', (string) $title, SQLITE3_TEXT);
        $stmt->bindValue(':category', (int) $category, SQLITE3_INTEGER);
        $stmt->bindValue(':description', (string) $description, SQLITE3_TEXT);
        $stmt->bindValue(':source', (string) $source, SQLITE3_TEXT);
        $stmt->bindValue(':datetime', $dt, SQLITE3_INTEGER);

        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        $result->finalize();
        $this->createRss();

        return true;
    }

    /**
     * Выборка всех записей из новостной ленты в обратном порядке.
     *
     * @return array|false
     */
    public function getNews(): array|false
    {
        $sql = "SELECT msgs.id as id, title, category.name as category, description, source, datetime
                FROM msgs, category
                WHERE category.id = msgs.category
                ORDER BY msgs.id DESC";

        $result = $this->_db->query($sql);
        if ($result === false) {
            return false;
        }

        $items = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $items[] = $row;
        }

        return $items;
    }

    /**
     * Удаление записи из новостной ленты по идентификатору.
     *
     * @param int $id Идентификатор удаляемой записи
     * @return bool
     */
    public function deleteNews($id): bool
    {
        $sql = "DELETE FROM msgs WHERE id = :id";
        $stmt = $this->_db->prepare($sql);
        if ($stmt === false) {
            return false;
        }

        $stmt->bindValue(':id', (int) $id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        return $result !== false && $this->_db->changes() > 0;
    }

    /**
     * Формирует и сохраняет RSS-файл с новостями с помощью DOMDocument.
     *
     * @return void
     */
    public function createRss(): void
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;

        $rss = $dom->createElement('rss');
        $dom->appendChild($rss);

        $version = $dom->createAttribute('version');
        $version->value = '2.0';
        $rss->appendChild($version);

        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);

        $channelTitle = $dom->createElement('title');
        $channelTitle->appendChild($dom->createTextNode(self::RSS_TITLE));
        $channel->appendChild($channelTitle);

        $channelLink = $dom->createElement('link');
        $channelLink->appendChild($dom->createTextNode(self::RSS_LINK));
        $channel->appendChild($channelLink);

        $lenta = $this->getNews();
        if (is_array($lenta)) {
            foreach ($lenta as $newsItem) {
                $item = $dom->createElement('item');

                $title = $dom->createElement('title');
                $title->appendChild($dom->createTextNode((string) $newsItem['title']));
                $item->appendChild($title);

                $link = $dom->createElement('link');
                $link->appendChild($dom->createTextNode(self::RSS_LINK . '#news-' . (int) $newsItem['id']));
                $item->appendChild($link);

                $description = $dom->createElement('description');
                $description->appendChild($dom->createCDATASection((string) $newsItem['description']));
                $item->appendChild($description);

                $pubDate = $dom->createElement('pubDate');
                $pubDate->appendChild($dom->createTextNode(date(DATE_RSS, (int) $newsItem['datetime'])));
                $item->appendChild($pubDate);

                $category = $dom->createElement('category');
                $category->appendChild($dom->createTextNode((string) $newsItem['category']));
                $item->appendChild($category);

                $channel->appendChild($item);
            }
        }

        $dom->save(__DIR__ . '/' . self::RSS_NAME);
    }
}
