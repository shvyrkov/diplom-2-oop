<?php

namespace App\Controllers;

use App\View\View;
use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Articles;

/**
 * Класс SiteController - контроллер для работы со страницами меню пользователя
 * @package App\Controllers
 */
class SiteController
{
    /**
     * Запускает Главную страниц
     * 
     * @return object View - объект представления Главной страницы со списком статей
     */
    public function index()
    {
        // Pagination
        $uri = View::getURI(); // Получаем строку запроса без корня
        $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы - только из корня ('/')
        $selected = Pagination::goodsQuantity($page);
        $page = $selected['page']; // Номер страницы
        $limit = Articles::getArticlesQtyOnPage(); // Количество товаров на странице
        $total = Articles::all()->count(); // Всего товаров в БД

        $data = [
            'title' => Menu::showTitle(Menu::getUserMenu()),
            'articles' => Articles::getArticles($limit, $page), // Статьи для вывода на страницу
            'pagination' => new Pagination($total, $page, $limit, 'page-'), // Постраничная навигация
            'total' =>  $total, // Всего товаров в БД
            'limit' =>  $limit, //  Количество товаров на странице
        ];

        return new View('homepage', $data); // Вывод представления
    }

    /**
     * Вывод страницы "О нас"
     *
     * @return View - объект представления страницы "О нас"
     */
    public function about()
    {
        return new View(
            'about', // about.php - имя файла с Представлением
            [
                'title' => Menu::showTitle(Menu::getUserMenu()),
            ]
        );
    }

    /**
     * Вывод страницы "Контакты"
     *
     * @return View - объект представления страницы "Контакты"
     */
    public function contacts()
    {
        return new View(
            'contacts',
            [
                'title' => Menu::showTitle(Menu::getUserMenu()),
            ]
        );
    }

    /**
     * Вывод страницы с правилами сайта
     *
     * @return View - объект представления страницы с правилами сайта
     */
    public function rules()
    {
        return new View(
            'rules',
            [
                'title' => Menu::showTitle(Menu::getUserMenu()),
            ]
        );
    }
}
