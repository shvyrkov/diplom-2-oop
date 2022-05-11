<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class PasswordView extends View
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
        $old_password = '';
        $new_password = '';
        $confirm_password = '';
        $errors = false;
        $success = '';
        $user = '';

        if (isset($_POST['submit'])) { // Обработка формы авторизации
            $email = $_POST['email'] ?? '';
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (!($old_password && $new_password && $confirm_password)) {
                $errors[] = 'Не все поля заполнены';
            }
           // Проверка ошибок ввода
            if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                $errors[] = 'Ошибка ввода. Обратитесь к администратору.';
            }

            if (!Users::checkEmailExists($email)) {
                $errors[] = 'Пользователь не существует. Обратитесь к администратору.';
            }

            // Check current password
            if (!Users::checkUserData($email, $old_password)) {
                $errors[] = 'Неправильный пароль.';
            }

            if (!Users::checkPassword($new_password)) {
                $errors['checkPassword'] = 'Пароль не должен быть короче 6-ти символов';
            }

            if (!Users::comparePasswords($new_password, $confirm_password)) {
                $errors['comparePasswords'] = 'Пароли не совпадают';
            }

            $passwordHash = password_hash($new_password, PASSWORD_DEFAULT);

            if ($errors === false) { // Если ошибок нет, то меняем пароль.
                Users::changePassword($email, $passwordHash); 
                
            // Проверяем правильно ли сменился пароль
                $user = Users::checkUserData($email, $new_password);

                if (!$user) {
                    // Если данные неправильные - показываем ошибку
                    $errors[] = 'Ошибка при смене пароля';
                } else {
                    $success = 'Пароль был успешно изменен!';
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
