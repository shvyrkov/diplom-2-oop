<?php
namespace App\Controllers;

use App\View\View;
use App\Components\Menu;

class PostController
{
    public function post()
    {
        return new View('post', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }
}
