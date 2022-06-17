<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Articles;
use App\View\AdminView;

// use App\View\AdminArticlesView;
use App\View\AdminCommentsView;
use App\View\AdminCMSView;


/**
 * Класс ArticleController - контроллер для работы со статьями в админке
 * @package App\Controllers\Admin
 */
class ArticleController
{
    /**
     * Вывод страницы 'Управление статьями'
     *
     * @return View
     */
    public function adminArticles()
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру

            $total = Articles::all()->count(); // Всего товаров в БД
            $uri = AdminView::getURI(); // Получаем строку запроса без корня
            // $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
            $page = ($uri == 'admin-articles') ? 1 : preg_replace('~admin-articles/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел /admin-articles, то - 1
            $selected = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
            $page = $selected['page']; // Номер страницы

            if ($selected['limit'] == 'all' || $selected['limit'] > $total) {
                $limit = $total;
            } else {
                $limit = $selected['limit']; // Количество статей на странице в админке 
            }

            return new AdminView(
                'admin-articles',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'articles' => Articles::getArticles($limit, $page), // Статей для вывода на страницу
                    'pagination' => new Pagination($total, $page, $limit, 'page-'), // Постраничная навигация
                    'total' =>  $total, // Всего товаров в БД
                    'limit' =>  $limit, //  Количество товаров на странице
                    'selected' =>  $selected, // Настройка количества товаров на странице
                ]
            ); // Вывод представления
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы 'Управление комментариями'
     *
     * @return View
     */
    public function adminComments()
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру

            return new AdminCommentsView('admin-comments', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы 'Управление статичными страницами' - создание/редактирование статьи
     *
     * @return AdminCMSView
     */
    public function adminCMS($id = 0)
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру

            return new AdminCMSView('admin-cms', ['title' => Menu::showTitle(Menu::getAdminMenu()), 'id' => $id]); // Вывод представления
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы-сообщения об удалении статьи.
     *
     * @return View
     */
    public function articleDelete($success = 0)
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру

            return new AdminView('article-delete', ['title' => Menu::showTitle(Menu::getAdminMenu()), 'success' => $success]); // Вывод представления
        } else {
            header('Location: /');
        }
    }
}
