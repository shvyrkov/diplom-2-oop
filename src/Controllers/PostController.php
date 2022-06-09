<?php

namespace App\Controllers;

use App\View\PostView;
use App\Components\Menu;

class PostController
{
    public function post()
    {
        return new PostView('post', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }
}
