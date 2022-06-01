<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\View\AdminView;
use App\View\AdminSettingsView;

class SiteController
{

    /**
     * Вывод страницы 'Дополнительные настройки'
     *
     * @return View
     */
    public function additionalSettings()
    {
        if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == ADMIN) { // Доступ разрешен только админу 

            return new AdminSettingsView('admin-settings', ['title' => Menu::showTitle(Menu::getAdminMenu())]); // Вывод представления
        } elseif (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == CONTENT_MANAGER) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню

            return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]);
        } else {
            header('Location: /'); // @TODO: Выводить текст: вы не авторизованы...
        }
    }
}
