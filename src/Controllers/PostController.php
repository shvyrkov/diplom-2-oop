<?php

namespace App\Controllers;

use App\View\View;
use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Post;

/**
 * Класс PostController - контроллер для работы с рассылками
 * @package App\Controllers
 */
class PostController extends AbstractController
{
    public function post()
    {
        $total = Post::all()->count(); // Всего товаров в БД
        $uri =  View::getURI(); // Получаем строку запроса без корня
        // $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
        $page = ($uri == 'post') ? 1 : preg_replace('~post/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел, то - 1
        $selected = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
        $page = $selected['page']; // Номер страницы

        if ($selected['limit'] == 'all' || $selected['limit'] > $total) {
            $limit = $total;
        } else {
            $limit = $selected['limit']; // Количество статей на странице в админке 
        }

        return new View(
            'post',
            [
                'title' => Menu::showTitle(Menu::getUserMenu()),
                'mails' => Post::getMails($limit, $page), // Статей для вывода на страницу
                'pagination' => new Pagination($total, $page, $limit, 'page-'), // Постраничная навигация
                'total' =>  $total, // Всего товаров в БД
                'limit' =>  $limit, //  Количество товаров на странице
                'selected' =>  $selected, // Настройка количества товаров на странице
            ]
        ); // Вывод представления
    }
}
