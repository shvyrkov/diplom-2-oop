<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\View\AdminView;
use App\View\AdminArticlesView;
use App\View\AdminCommentsView;
use App\View\AdminCMSView;

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

            return new AdminArticlesView('admin-articles', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } else {
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...?
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
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...?
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
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...?
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
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...?
        }
    }
}
