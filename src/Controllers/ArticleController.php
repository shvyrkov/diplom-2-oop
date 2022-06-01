<?php

namespace App\Controllers;

use App\View\View;
use App\View\ArticleView;

class ArticleController
{
    /**
     * Вывод страницы выбранной статьи
     *
     * @var string $id - данные строки запроса - id-статьи
     *
     * @return ArticleView - объект представления страницы выбранной статьи
     */
    public function article($id)
    {
        return new ArticleView('article', ['id' => $id]);
    }

    /**
     * @TEST: Метод принимает значения $params из строки запроса и выдает их обратно в виде строки опред-го вида...
     *
     */
    public function test(...$params)
    {
        $string = "Test Page With : ";
        $i = 1;

        foreach ($params as $param) {
            $string .= ' param_' . $i++ . ' = ' . $param;
        }
        // echo "<pre>";
        // echo "<br>_POST:<br>";
        // var_dump($_POST);
        // echo "<br>_GET:<br>";
        // var_dump($_GET);
        // echo "<br>SERVER:<br>";
        // var_dump($_SERVER);
        // echo "</pre>";

        return $string;
    }

    /**
     * @TEST: Метод принимает значения $params из строки запроса и выдает их обратно в виде строки опред-го вида...
     *
     */
    public function index(...$params)
    {
        $params = [ // ???

            'title' => 'Главная', // Название пункта меню
            'path' => '/', // Ссылка на страницу, куда ведет этот пункт меню
            'class' => SiteController::class, // ?
            'method' => 'index', // ?
            'sort' => 0, // Индекс сортировки (?)

        ];

        return new View($params['path'], [
            'title' => 'Контакты',
            'link_1' => '/', 'linkText_1' => 'На главную',
            'link_2' => '/about', 'linkText_2' => 'О нас',
            'link_3' => '/post', 'linkText_3' => 'Почта'
        ]); // Вывод представления
    }
}
