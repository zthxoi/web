<?php
namespace Factory\Models;

class Users extends Collection
{
    public function __construct(public ?array $users = null)
    {
        $users ??= [
            new User(
                'dmitry.koterov@gmail.com',
                'password',
                'Дмитрий',
                'Котеров'),
            new User(
                'igorsimdyanov@gmail.com',
                'password',
                'Игорь',
                'Симдянов'),
            new User(
                'ivan.ivanov@example.com',
                'pass111',
                'Иван',
                'Иванов'),
            new User(
                'petr.petrov@example.com',
                'pass222',
                'Пётр',
                'Петров'),
            new User(
                'anna.smirnova@example.com',
                'pass333',
                'Анна',
                'Смирнова'),
            new User(
                'elena.kuznetsova@example.com',
                'pass444',
                'Елена',
                'Кузнецова'),
            new User(
                'dmitry.sokolov@example.com',
                'pass555',
                'Дмитрий',
                'Соколов')
        ];
        parent::__construct($users);
    }
}