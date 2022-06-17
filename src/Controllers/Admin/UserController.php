<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\View\AdminView;
use App\View\AdminUsersView;
use App\View\AdminSubscriptionView;

/**
 * Класс UserController - контроллер для работы с пользователями в админке
 * @package App\Controllers\Admin
 */
class UserController
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
        } else {
            header('Location: /');
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
            header('Location: /');
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
            header('Location: /');
        }
    }
}
