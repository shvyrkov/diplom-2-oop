<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;

/**
* Класс LkView — управление выводом view страницы.
*/
class SubscriptionView extends View
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
        $data = extract($this->data); // ['id' => 'id_article'] -> $id = 'id_article' - создается переменная для исп-я в html
        $menu = Menu::getUserMenu();
        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        // Подписка
        $result = false;
        $errors = false;
        $user = false;
        $id = $_SESSION['user']['id'] ?? '';

        if (isset($_POST['subscribeAuthUser'])) {
            if ($id) { // Если пользователь авторизован, то подписываем его на рассылку (кнопка Пописаться видна только у неподписанных пользователей)
                $result = Users::changeSubscription($id, 1);

                if ($result) { // Только для авторизоанного пользователя запрашиваем новые данные 
                    $user = Users::getUserById($id);
                }

                if ($user === false) {
                    // Если данные не получены - показываем ошибку
                    $errors[] = 'Ошибка получения данных.';
                } else {
                    // Если данные правильные, запоминаем пользователя в сессии
                    Users::auth($user);
                }
            }
        }

        if (isset($_POST['subscribeNotAuthUser'])) { // Если пользователь НЕавторизован, то переводим его на страницу подписки для ввода e-mail

            $email = $_POST['email'] ?? '';
            // Валидация e-mail
            if (!$email) {
                $errors[] = 'Введите e-mail';
            }

            if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                $errors['checkEmail'] = ' Неправильный email';
            }

            if (Users::checkEmailExists($email) ) { //  Есть пользователь
                $user = Users::getUserByEmail($email);

                if ($user->name) { // уже авторизованный 
                    $errors['checkEmailExists'] = ' Такой email уже используется, авторизуйтесь пожалуйста.';
                } elseif ($user->subscription) { // НЕавторизованный, но подписанный пользователь
                    $errors['checkEmailExists'] = ' Вы уже подписаны на рассылку.';
                }
            }

            if ($errors === false) { // Если ошибок нет, то подписываем пользователя на рассылку.
                if (!$user) {
                    $user = new Users(); // Если пользователя нет в БД, то регистрируем его 
                }

                $user->email = $email;
                $user->role = NO_USER; // как NO_USER
                $user->subscription = 1; // и подписываем его на рассылку
                $user->save();
                $id = $user->id;

                $user = Users::getUserById($id); // Запрашиваем данные подписанного пользователя из БД

                if ($user->subscription) { // Если у него есть подписка, то всё Ок.
                    $result = true;
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
