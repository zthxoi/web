<?php
declare(strict_types=1);
namespace MyProject\Classes;
require_once __DIR__ . '/AbstractUser.php';
class User extends AbstractUser {
    public string $name;
    public string $login;
    private string $password;
    public static int $userCount = 0;
    public function __construct(string $name, string $login, string $password) {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        if (get_class($this) === self::class) {
            self::$userCount++;
        }
    }
    public function __destruct() {
        echo "Пользователь " . $this->login . " удален.<br>";
    }
    public function showInfo(): void {
        echo "👤 Имя: " . $this->name . "<br>";
        echo "🔑 Логин: " . $this->login . "<br>";
        echo "🔒 Пароль: " . $this->password . "<br>";
        echo "<hr>";
    }
}