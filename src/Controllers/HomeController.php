<?php
namespace App\Controllers;

use App\View\HomeView;
use App\View\MethodView;
use App\Components\Menu;

class HomeController
{
    /**
     * Запускает Главную страниц
     * 
     * @param int $page - номер страницы в пагинации
     * 
     * @return object View - объект представления страницы
     */
    public function index($articlesOnPage = 4)
    {
        $data = ['title' => Menu::showTitle(Menu::getUserMenu()), 'articlesOnPage' => $articlesOnPage];

        return new HomeView('homepage', $data); // Вывод представления
    }

    /**
     * Запускает Главную страниц
     * 
     * @param int $page - номер страницы в пагинации
     * 
     * @return object View - объект представления страницы
     */
    public function method()
    {
        $data = ['title' => Menu::showTitle(Menu::getUserMenu()), 'method' => 3];

        return new MethodView('method', $data); // Вывод представления
    }
/**
* Метод принимает значения $params из строки запроса и выдает их обратно в виде строки опред-го вида...
*
*/
    public function test(...$params)
    {
        $string = "Test Page With : ";
        $i = 1;

        foreach ($params as $param) {
            $string .= ' param_' . $i++ . ' = ' . $param;
        }

        return $string;
    }
}
