<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;

/**
 * Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
 */
class RegistrationView extends View
{
    /**
     * Метод выводит необходимый шаблон.
     */
    public function render()
    {
        $data = extract($this->data); // ['id' => 'id_article'] -> $id = 'id_article' - создается переменная для исп-я в html
        $menu = Menu::getUserMenu();
        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        $name = '';
        $email = '';
        $password = '';
        $confirm_password = '';
        $rules = '';

        if (isset($_POST['submit'])) { // Обработка формы авторизации
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $rules = $_POST['rules'];

            $errors = false;

            if (!(isset($name) && isset($email) && isset($password) && isset($confirm_password) && isset($rules))) {
                $errors[] = 'Не все поля заполнены';
            }

            // Проверка ошибок ввода
            if (!Users::checkName($name)) {
                $errors['checkName'] = ' - не должно быть короче 2-х символов';
            }

            if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                $errors['checkEmail'] = ' - неправильный email';
            }

            if (!Users::checkPassword($password)) {
                $errors['checkPassword'] = ' - не должен быть короче 6-ти символов';
            }

            if (!Users::comparePasswords($password, $confirm_password)) {
                $errors['comparePasswords'] = ' - пароли не совпадают';
            }

            if (Users::checkEmailExists($email)) {
                $errors['checkEmailExists'] = ' - такой email уже используется';
            }

            if (Users::checkNameExists($name)) {
                $errors['checkNameExists'] = ' - такое имя уже используется';
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            if ($errors === false) { // Если ошибок нет, то регистрируем пользователя.
                Users::register($email, USER, $name, $passwordHash);

                // Проверяем зарегистрировался ли пользователь
                $user = Users::checkUserData($email, $password);

                if ($user === false) {
                    // Если данные неправильные - показываем ошибку
                    $errors[] = 'Регистрация не прошла.';
                    // Если регистрация на прошла - опять на страницу регистрациии
                } else {
                    // Если данные правильные, запоминаем пользователя в сессии
                    Users::auth($user);

                    // Перенаправляем пользователя в закрытую часть - кабинет 
                    header('Location: /lk');

                    return new View('lk', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
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
