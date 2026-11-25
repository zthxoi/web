<?php
declare(strict_types=1);


class NumbersSquared implements Iterator
{
    private int $start;
    private int $end;
    private int $current;

    /**
     * Конструктор класса NumbersSquared.
     *
     * @param int $start Первое число
     * @param int $end Последнее число
     */
    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->current = $start;
    }

    /**
     * Сбрасывает итератор на начало последовательности.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->current = $this->start;
    }

    /**
     * Проверяет, не вышло ли текущее значение за пределы диапазона.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->current <= $this->end;
    }

    /**
     * Переходит к следующему числу последовательности.
     *
     * @return void
     */
    public function next(): void
    {
        $this->current++;
    }

    /**
     * Возвращает текущее число (ключ).
     *
     * @return int
     */
    public function key(): int
    {
        return $this->current;
    }

    /**
     * Возвращает квадрат текущего числа (значение).
     *
     * @return int
     */
    public function current(): int
    {
        return $this->current ** 2;
    }
}

$obj = new NumbersSquared(3, 7);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №4 — NumbersSquared</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 0 15px; line-height: 1.6; }
        pre { background: #f5f5f5; padding: 12px; border-radius: 4px; }
        img { max-width: 100%; height: auto; border: 1px solid #ddd; padding: 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Лабораторная работа №4: Итератор NumbersSquared</h1>
    <pre><?php
foreach ($obj as $num => $square) {
    echo "Квадрат числа $num = $square\n";
}
?></pre>
    <h2>Диаграмма классов</h2>
    <img src="numbers_squared.png" alt="Диаграмма классов NumbersSquared">
</body>
</html>
