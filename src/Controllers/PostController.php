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
    /**
     * Вывод страницы с логом рассылок по подписке
     *
     * @return View 
     */
    public function mailingLog()
    {
        $paginationData = $this->getPaginationData(Post::class, View::class, 'post', '~post/' . PAGINATION_PAGE . '([0-9]+)~');

        return new View(
            'post',
            [
                'title' => Menu::showTitle(Menu::getUserMenu()),
                'mails' => Post::getMails($paginationData['limit'], $paginationData['page']), // Статей для вывода на страницу
                'pagination' => new Pagination($paginationData['total'], $paginationData['page'], $paginationData['limit'], PAGINATION_PAGE), // Постраничная навигация
                'total' =>  $paginationData['total'], // Всего товаров в БД
                'limit' =>  $paginationData['limit'], //  Количество товаров на странице
                'selected' =>  $paginationData['selected'] // Настройка количества товаров на странице
            ]
        );
    }
}
