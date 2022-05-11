<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class LoginView extends View
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
        $data = extract($this->data); // ['id' => 'id_article'] -> $id = 'id_article' - создается переменная для исп-я в html
        $menu = Menu::getUserMenu();
        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        $email = '';
        $password = '';
        
        if (isset($_POST['submit'])) { // Обработка формы авторизации
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            // Валидация полей
            if (!Users::checkEmail($email)) {
                $errors['email'] = 'Неправильный email';
            } else {
                $user = Users::checkUserData($email, $password);

                if ($user) {
                    // Если данные правильные, запоминаем пользователя (сессия)
                    Users::auth($user);
                    // Перенаправляем пользователя в закрытую часть - кабинет 
                    header('Location: /lk');

                    return new View('lk', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
                } else {
                    // Если данные неправильные - показываем ошибку
                    $errors[] = 'Неправильные данные для входа.<br>
                                Возможно нажата клавиша CapsLock или несоответствующая раскладка клавиатуры';
                }
            }
        }

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else { // Если файл не найден
            throw new ApplicationException("$templateFile - шаблон не найден", 443); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}