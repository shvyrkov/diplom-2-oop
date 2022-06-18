<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Roles;
use App\Model\Users;
use App\View\AdminView;

use App\View\AdminSubscriptionView;
// use App\View\AdminUsersView;


/**
 * Класс UserController - контроллер для работы с пользователями в админке
 * @package App\Controllers\Admin
 */
class UserController
{
    /**
     * Вывод страницы Административной панели
     *
     * @return AdminView
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
     * @return AdminView
     */
    public function adminUsers()
    {
        if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == ADMIN) { // Доступ разрешен только админу 
            $errors = false;

            if (isset($_POST['submit'])) { // Измененине роли пользователя
                $userId = $_POST['userId'] ?? '';
                $role = $_POST['role'] ?? '';

                // Валидация полей
                if (!(is_numeric($userId) && is_numeric($role))) { // Индексы д.б.целыми числами.
                    $errors[] = 'Некорректные данные. Обратитесь к администртору!';
                } else {
                    Users::changeRole($userId, $role);
                }
            }

            $total = Users::all()->count(); // Всего пользователей в БД
            $uri = AdminView::getURI() ?? ''; // Получаем строку запроса без корня
            $page = ($uri == 'admin-users') ? 1 : preg_replace('~admin-users/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел /admin-articles, то - 1
            $selected = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
            $page = $selected['page']; // Номер страницы

            if ($selected['limit'] == 'all' || $selected['limit'] > $total) {
                $limit = $total;
            } else {
                $limit = $selected['limit']; // Количество статей на странице в админке 
            }

            return new AdminView(
                'admin-users',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'users' => Users::getUsers($limit, $page), // Пользователи
                    'roles' => Roles::all(), // Роли пользователей
                    'pagination' => new Pagination($total, $page, $limit, 'page-'), // Постраничная навигация
                    'total' =>  $total, // Всего товаров в БД
                    'limit' =>  $limit, //  Количество товаров на странице
                    'selected' =>  $selected, // Настройка количества товаров на странице
                    'errors' => $errors
                ]
            ); // Вывод представления
        } elseif (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == CONTENT_MANAGER) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню

            return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]);
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы 'Управление подписками'
     *
     * @return AdminView
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
