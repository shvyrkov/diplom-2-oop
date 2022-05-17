<?php
namespace App\Controllers;

use App\Components\Menu;
use App\View\LoginView;
use App\View\AdminView;
use App\View\AdminUsersView;
use App\View\AdminArticlesView;
use App\View\AdminSubscriptionView;
use App\View\AdminCommentsView;
use App\View\AdminCMSView;
use App\View\AdminSettingsView;

class AdminPageController
{
    /**
    * Вывод страницы Административной панели
    *
    * @return View
    */
    public function admin()
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру

            return new AdminView('admin', ['title' => 'Админка']); // Вывод представления
            // return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } else {
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...?
        }
    }

    /**
    * Вывод страницы 'Управление пользователями'
    *
    * @return View
    */
    public function adminUsers()
    {
        if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == ADMIN) { // Доступ разрешен только админу 

            return new AdminUsersView('admin-users', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } elseif (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == CONTENT_MANAGER) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню

            return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]);
        } else {
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...
        }
    }

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
    * Вывод страницы 'Управление подписками'
    *
    * @return View
    */
    public function adminSubscription()
    {
        if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == ADMIN) { // Доступ разрешен только админу 

            return new AdminSubscriptionView('admin-subscription', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } elseif (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == CONTENT_MANAGER) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню

            return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]);
        } else {
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...
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
    * Вывод страницы 'Управление статичными страницами'
    *
    * @return View
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
    * Успешная запись статьи
    *
    * @return View
    */
    // public function articleAdded()
    // {
    //     return new View('article-added', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
    // }

    /**
    * Вывод страницы 'Дополнительные настройки'
    *
    * @return View
    */
    public function adminSettings()
    {
        if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == ADMIN) { // Доступ разрешен только админу 

            return new AdminSettingsView('admin-settings', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } elseif (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == CONTENT_MANAGER) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню

            return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]);
        } else {
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...
        }
    }


// -----------------Test--------------------------------------
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

    public function index(...$params)
    {
        $params = [ // ???
            
               'title' => 'Главная', // Название пункта меню
               'path' => '/', // Ссылка на страницу, куда ведет этот пункт меню
               'class' => HomeController::class, // ?
               'method' => 'index', // ?
               'sort' => 0, // Индекс сортировки (?)
            
        ];

        return new View($params['path'], ['title' => 'Контакты', 
            'link_1' => '/', 'linkText_1' => 'На главную', 
            'link_2' => '/about', 'linkText_2' => 'О нас', 
            'link_3' => '/post', 'linkText_3' => 'Почта']); // Вывод представления
    }
}
