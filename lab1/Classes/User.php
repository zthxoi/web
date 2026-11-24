<?php
declare(strict_types=1);

namespace MyProject\Classes;

class User extends AbstractUser
{
    public string $name;
    public string $login;
    private string $password;
    public static int $userCount = 0;

    public function __construct(string $name, string $login, string $password)
    {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;

        if (static::class === self::class) {
            self::$userCount++;
        }
    }

    public function __destruct()
    {
        echo "<p>Пользователь {$this->login} удален.</p>\n";
    }

    public function showInfo(): void
    {
        echo "<p>";
        echo "<strong>Имя:</strong> {$this->name}<br>";
        echo "<strong>Логин:</strong> {$this->login}<br>";
        echo "<strong>Пароль:</strong> {$this->password}";
        echo "</p>\n";
    }
}
