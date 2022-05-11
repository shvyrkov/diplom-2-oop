<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Settings;
use App\View\AdminUsersView;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class AdminSettingsView extends AdminView
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
     /** метод должен выводить необходимый шаблон. Внутри метода данные свойства $data распаковать в переменные через extract(), а затем подключить страницу шаблона, получив полный путь к ней с помощью другого метода этого класса getIncludeTemplate().
    */

        if (isset($_POST['submit'])) { // Измененине Настройки
            $id = $_POST['id'];
            $value = $_POST['value'];

            // Валидация полей
            if (!(is_numeric($id) && is_numeric($value))) { // Индексы д.б.целыми числами.
                $errors[] = 'Некорректные данные. Обратитесь к администртору!';
            } else {
                Settings::changeSetting($id, $value);
            }
        }

        $settings = Settings::all(); // Настройки

        extract($this->data); // ['title' => 'Index Page'] -> $title = 'Index Page' - создается переменная для исп-я в html
        $menu = Menu::getAdminMenu();

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (isset($_POST['exit'])) {
            Users::exit();
        }

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else { // Если файл не найден
            throw new ApplicationException("$templateFile - шаблон не найден", 442); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}
