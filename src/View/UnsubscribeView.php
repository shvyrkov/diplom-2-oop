<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;

/**
* Класс LkView — управление выводом view страницы.
*/
class UnsubscribeView extends View
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
        $data = extract($this->data); // ['id' => 'id_article'] -> $id = 'id_article' - создается переменная для исп-я в html
        $menu = Menu::getUserMenu();
        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        // Отписка - @TODO-------------------------
        $result = false;
        $errors = false;
        $user = false;
        $email = $_POST['email'] ?? '';

        if (isset($_POST['unsubscribe'])) {
            // Валидация e-mail
            if (!$email) {
                $errors[] = 'Введите e-mail';
            }

            if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                $errors['checkEmail'] = ' Неправильный email';
            } elseif (Users::checkEmailExists($email) ) { //  Если есть пользователь с таким e-mail
                $user = Users::getUserByEmail($email);

                if (!$user->subscription) { // и он не подписан
                    $errors['checkEmailExists'] = ' Вы не подписаны на рассылку.';
                }
            } else {
                $errors['checkEmailExists'] = ' Вы не подписаны на рассылку.';
            }

            if ($errors === false) {
                if ($user) { // Если пользователь с таким e-mail есть, то
                    $result = Users::changeSubscription($user->id, 0); // отписываем его от рассылки.

                    if (!$result) {
                        $errors[] = 'Ошибка обработки данных. Обратитесь к Администратору.';
                    }
                } else { // если нет, то выдаем ошибку
                    $errors[] = 'Ошибка получения данных. Обратитесь к Администратору.';
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
