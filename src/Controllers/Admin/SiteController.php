<?php

namespace App\Controllers\Admin;

use App\Components\Menu;
use App\Model\Settings;
use App\Validator\SiteValidator;
use App\View\AdminView;

/**
 * Класс SiteController - контроллер для работы со страницами меню в админке
 * @package App\Controllers\Admin
 */
class SiteController extends \App\Controllers\AbstractPrivateController
{

    /**
     * Вывод страницы 'Дополнительные настройки'
     *
     * @return AdminView
     */
    public function additionalSettings()
    {
        if (in_array($this->user->role, [ADMIN])) { // Доступ разрешен только админу 
            if (isset($_POST['submit'])) { // Измененине Настройки
                $id = $_POST['id'] ?? 0;
                $value = $_POST['value'] ?? 0;

                $errors = SiteValidator::adminSettingsValidate($id, $value);

                if (!$errors) {
                    Settings::changeSetting($id, $value);
                    $this->redirect('/admin-settings');
                } else {
                    $errors[] = 'Изменение настройки не прошло';
                }
            }

            return new AdminView(
                'admin-settings',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'settings' => Settings::all(),
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
