<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Roles;
use App\Model\Users;
use App\Validator\UserValidator;
use App\View\AdminView;

/**
 * Класс UserController - контроллер для работы с пользователями в админке
 * @package App\Controllers\Admin
 */
class UserController extends \App\Controllers\AbstractPrivateController
{
    /**
     * Вывод Главной страницы Административной панели
     *
     * @return AdminView
     */
    public function admin()
    {
        if (in_array($this->user->role, [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру
            return new AdminView('admin', ['title' => 'Админка']);
        } else {
            $this->redirect('/lk');
        }
    }

    /**
     * Вывод страницы 'Управление пользователями'
     *
     * @return AdminView
     */
    public function adminUsers()
    {
        if (in_array($this->user->role, [ADMIN])) { // Доступ разрешен только админу 

            $paginationData = $this->getPaginationData(Users::class, AdminView::class, 'admin-users', '~admin-users/' . PAGINATION_PAGE . '([0-9]+)~');

            if (isset($_POST['submit'])) {
                $userId = $_POST['userId'] ?? 0;
                $role = $_POST['role'] ?? 0;

                $errors = UserValidator::adminUserValidate($userId, $role);

                if (!$errors) {
                    Users::changeRole($userId, $role);
                    $this->redirect('/admin-users/' . PAGINATION_PAGE . $paginationData['page']);
                }else {
                    $errors[] = 'Изменение роли пользователя не прошло';
                }
            }

            return new AdminView(
                'admin-users',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'users' => Users::getUsers($paginationData['limit'], $paginationData['page']), // Пользователи
                    'roles' => Roles::all(), // Роли пользователей
                    'pagination' => new Pagination($paginationData['total'], $paginationData['page'], $paginationData['limit'], PAGINATION_PAGE), // Постраничная навигация
                    'total' =>  $paginationData['total'], // Всего товаров в БД
                    'limit' =>  $paginationData['limit'], //  Количество товаров на странице
                    'selected' =>  $paginationData['selected'], // Настройка количества товаров на странице
                    'errors' => $errors ?? null
                ]
            );
        } elseif (in_array($this->user->role, [CONTENT_MANAGER])) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню
            $this->redirect('/admin');
        } else {
            $this->redirect('/lk');
        }
    }

    /**
     * Вывод страницы 'Управление подписками'
     *
     * @return AdminView
     */
    public function adminSubscription()
    {
        if (in_array($this->user->role, [ADMIN])) { // Доступ разрешен только админу 

            $paginationData = $this->getPaginationData(Users::class, AdminView::class, 'admin-subscription', '~admin-subscription/' . PAGINATION_PAGE . '([0-9]+)~');

            if (isset($_POST['submit'])) { // Измененине подписки на рассылку пользователя
                $userId = $_POST['id'] ?? 0;
                $subscription = $_POST['subscription'] ?? 0;

                $errors = UserValidator::adminUserSubscriptionValidate($userId, $subscription);

                if (!$errors) {
                    Users::changeSubscription($userId, $subscription);
                    $this->redirect('/admin-subscription/' . PAGINATION_PAGE . $paginationData['page']);
                } else {
                    $errors[] = 'Изменение подписки не прошло';
                }
            }

            return new AdminView(
                'admin-subscription',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'users' => Users::getUsers($paginationData['limit'], $paginationData['page']), // Пользователи
                    'pagination' => new Pagination($paginationData['total'], $paginationData['page'], $paginationData['limit'], PAGINATION_PAGE), // Постраничная навигация
                    'total' =>  $paginationData['total'], // Всего товаров в БД
                    'limit' =>  $paginationData['limit'], //  Количество товаров на странице
                    'selected' =>  $paginationData['selected'], // Настройка количества товаров на странице
                    'errors' => $errors ?? null
                ]
            );
        } elseif (in_array($this->user->role, [CONTENT_MANAGER])) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню
            $this->redirect('/admin');
        } else {
            $this->redirect('/lk');
        }
    }
}
