<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;
use App\Model\Comments;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class AdminCommentsView extends AdminView
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
     /** метод должен выводить необходимый шаблон. Внутри метода данные свойства $data распаковать в переменные через extract(), а затем подключить страницу шаблона, получив полный путь к ней с помощью другого метода этого класса getIncludeTemplate().
    */

        $id = '';
        $text = '';
        $approve = 0;
        $deny = 0;
        $errors = false;

        if (isset($_POST['submit'])) { // Измененине роли пользователя
            $id = $_POST['id'];
            $approve = $_POST['approve'] ?? 0;
            $deny = $_POST['deny'] ?? 0;

            // Валидация полей
            if (!(is_numeric($id) && in_array($approve, [0, 1]) && in_array($deny, [0, 1]))) { // Индексы д.б.целыми числами.
                $errors[] = 'Некорректные данные. Обратитесь к администртору!';
            }

            if ($errors === false) { // Если ошибок нет, то
                Comments::changeComment($id, $approve, $deny);
            }
        }

        $comments = Comments::getComments();

        if (isset($_POST['exit'])) {
            Users::exit();
        }

        extract($this->data); // ['title' => 'Index Page'] -> $title = 'Index Page' - создается переменная для исп-я в html
        $menu = Menu::getAdminMenu();

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else { // Если файл не найден
            throw new ApplicationException("$templateFile - шаблон не найден", 442); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}
