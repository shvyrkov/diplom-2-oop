<?php
namespace App\Controllers;

use App\View\View;

class NotFoundController // @TODELETE
{
    public function index()
    {
        return new View('errors', ['title' => 'Ошибка 404. Страница не найдена', 'linkText' => 'На главную']); // Вывод представления
    }
}
