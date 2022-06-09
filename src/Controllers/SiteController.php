<?php

namespace App\Controllers;

use App\View\View;
use App\View\HomeView;
use App\View\MethodView;
use App\Components\Menu;

class SiteController
{
    /**
     * Запускает Главную страниц
     * 
     * @param int $page - номер страницы в пагинации
     * 
     * @return object View - объект представления Главной страницы со списком статей
     */
    public function index($articlesOnPage = 4)
    {
        $data = ['title' => Menu::showTitle(Menu::getUserMenu()), 'articlesOnPage' => $articlesOnPage];

        return new HomeView('homepage', $data); // Вывод представления
    }

    /**
     * Вывод меню типов (методов) статей 
     *  
     * @return object MethodView - объект представления вывода меню типов (методов) статей
     */
    public function method()
    {
        $data = ['title' => Menu::showTitle(Menu::getUserMenu()), 'method' => 3];

        return new MethodView('method', $data); // Вывод представления
    }

    /**
     * Вывод страницы "О нас"
     *
     * @return View - объект представления страницы "О нас"
     */
    public function about()
    {
        return new View('about', ['title' => Menu::showTitle(Menu::getUserMenu())]); // about.php - имя файла с Представлением (personal.messages.show -> __DIR__ . VIEW_DIR . 'personal/messages/show.php')
    }

    /**
     * Вывод страницы "Контакты"
     *
     * @return View - объект представления страницы "Контакты"
     */
    public function contacts()
    {
        return new View('contacts', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }

    /**
     * Вывод страницы с правилами сайта
     *
     * @return View - объект представления страницы с правилами сайта
     */
    public function rules()
    {
        return new View('rules', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }
}
