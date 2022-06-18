<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\Model\Settings;
use App\View\AdminView;

/**
 * Класс SiteController - контроллер для работы со страницами меню в админке
 * @package App\Controllers\Admin
 */
class SiteController
{

    /**
     * Вывод страницы 'Дополнительные настройки'
     *
     * @return AdminView
     */
    public function additionalSettings()
    {
        $errors = false;
        $result = false;

        if (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == ADMIN) { // Доступ разрешен только админу 
            if (isset($_POST['submit'])) { // Измененине Настройки
                $id = $_POST['id'];
                $value = $_POST['value'];
                // Валидация полей
                if (!(is_numeric($id) && is_numeric($value))) { // Индексы д.б.целыми числами.
                    $errors[] = 'Некорректные данные. Обратитесь к администртору!';
                } else {
                    $result = Settings::changeSetting($id, $value);

                    if ($result === false) {
                        $errors[] = 'Транзакция не прошла. Обратитесь к администртору!';
                    }
                }
            }

            return new AdminView(
                'admin-settings',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'settings' => Settings::all(), // Настройки
                    'errors' => $errors
                ]
            ); // Вывод представления
        } elseif (isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == CONTENT_MANAGER) { // Если контент-менеджер пытается зайти в админскую часть, то кидаем его в админ-меню

            return new AdminView('admin', ['title' => Menu::showTitle(Menu::getAdminMenu())]);
        } else {
            header('Location: /');
        }
    }
}
