<?php

namespace App\Controllers;

use \SplFileInfo;
use App\Components\Menu;
use App\Components\SimpleImage;
use App\Model\Users;
use App\Model\Roles;
use App\View\View;
// use App\View\LoginView;
use App\View\RegistrationView;
// use App\View\LkView;
use App\View\PasswordView;
use App\View\UnsubscribeView;
use App\View\SubscriptionView;

/**
 * Класс UserController - контроллер для работы с пользователем
 */

class UserController
{
    /**
     * Вывод страницы авторизации пользователя
     *
     * @return object LoginView - объект представления страницы авторизации пользователя
     */
    public function login()
    {
        if (isset($_POST['submit'])) { // Обработка формы авторизации
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Валидация полей
            if (!Users::checkEmail($email)) {
                $errors['email'] = 'Некорректный email';
            } else {
                $user = Users::checkUserData($email, $password);

                if ($user) {
                    // Если данные правильные, запоминаем пользователя (сессия)
                    Users::auth($user);
                    // Перенаправляем пользователя в закрытую часть - кабинет 
                    header('Location: /lk');
                } else {
                    // Если данные неправильные - показываем ошибку
                    $errors['wrongData'] = 'Неправильные данные для входа.<br>
                                Возможно нажата клавиша CapsLock или несоответствующая раскладка клавиатуры';
                }
            }
        }

        return new View(
            'login',
            [
                'title' => 'Вход',
                'email' =>  $email ?? '',
                'password' => $password ?? '',
                'errors' =>  $errors ?? '',
            ]
        );
    }

    /**
     * Выход из сессии
     *
     * @return object View - объект представления страницы после выхода пользователя из приложения
     */
    public function exit()
    {
        return new View('exit', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }

    /**
     * Подписка на рассылку для неподписанных пользователей.
     *
     * @return UnsubscribeView - объект представления страницы подписки на рассылку для неподписанных пользователей.
     */
    public function unsubscribe()
    {
        return new UnsubscribeView('unsubscribe', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }

    /**
     * Подписка на рассылку
     * 
     * @return object SubscriptionView - объект представления страницы подписки на рассылку
     */
    public function subscription()
    {
        $data = ['title' => Menu::showTitle(Menu::getUserMenu())];

        return new SubscriptionView('subscription', $data); // Вывод представления
    }

    /**
     * Вывод страницы регистрации пользователя
     *
     * @return RegistrationView - объект представления страницы регистрации нового пользователя
     */
    public function registration()
    {
        return new RegistrationView('registration', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
    }

    /**
     * Вывод страницы личного кабинета пользователя
     *
     * @return View - объект представления страницы личного кабинета пользователя
     */
    public function lk()
    {
        if (isset($_SESSION['user']['id'])) {
            $name = '';
            $email = '';
            $subscription = '';
            $aboutMe = '';
            $errors = false;

            if (isset($_POST['submit'])) { // Обработка формы ЛК
                $name = $_POST['name'];
                $email = $_POST['email'];
                $aboutMe = $_POST['aboutMe'];

                // Проверка ошибок ввода
                if (!Users::checkName($name)) {
                    $errors['checkName'] = '- не должно быть короче 2-х символов';
                }

                if (!($name == $_SESSION['user']['name'])) { // Если имя было изменено
                    if (Users::checkNameExists($name)) {
                        $errors['checkNameExists'] = '- такое имя уже используется';
                    }
                }

                if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                    $errors['checkEmail'] = '- неправильный email';
                }

                if (!($email == $_SESSION['user']['email'])) { // Если email был изменен
                    if (Users::checkEmailExists($email)) {
                        $errors['checkEmailExists'] = '- такой email уже используется';
                    }
                }

                if ($_FILES['myfile']['name'] != '') { // Проверка на наличие файла для загрузки
                    $types = include(CONFIG_DIR . IMAGE_TYPES);
                    $fileError = SimpleImage::imageFileValidation($types, FILE_SIZE, $_FILES); // Валидация файла изображения

                    if ($fileError) {
                        $errors['file'] = $fileError; // Если валидация не прошла, то добавляем её ошибки
                    }

                    if ($errors === false) { // Загружаем файл на сервер

                        if ((DEFAULT_AVATAR != $_SESSION['user']['avatar']) // Если это не заставка 
                            // && ($name != $_SESSION['user']['name']) // и было изменено имя пользователя,
                            && file_exists(AVATAR_STORAGE . $_SESSION['user']['avatar'])
                        ) {
                            unlink(AVATAR_STORAGE . $_SESSION['user']['avatar']); // то удаляем старый аватар на сервере 
                        }

                        $myfile = new SplFileInfo($_FILES['myfile']['name']); // Загружаемое имя файла с расширением
                        $fileName = $name ? $name : $_SESSION['user']['name']; // Имя файла без расширения: новое, если было изменено, иначе - старое
                        $fileName = $fileName . '.' . $myfile->getExtension(); // Имя файла с расширением
                        // $fileName = $name . '.' . $myfile->getExtension(); // Имя файла с расширением
                        $fileMoved = move_uploaded_file($_FILES['myfile']['tmp_name'], AVATAR_STORAGE . $fileName); // Загрузка файла на сервер

                        if (!$fileMoved) {
                            $errors['file']['LoadServerError'] = 'Файл ' . $fileName . ' не был загружен на сервер';
                        }
                    }
                }

                if ($errors === false) { // Если ошибок нет, то обновляем данные пользователя.
                    if (Users::updateUser($_SESSION['user']['id'], $name, $email, $aboutMe, ($_FILES['myfile']['name'] != '') ? $fileName : $_SESSION['user']['avatar'])) { // Если обновление прошло нормально

                        // Получаем обновленные данные пользователя
                        $user = Users::getUserById($_SESSION['user']['id']);

                        if (!$user) {
                            // Если данные не получены - показываем ошибку
                            $errors[] = 'Ошибка получения данных.';
                        } else {
                            // Если данные правильные, запоминаем пользователя в сессии
                            Users::auth($user);

                            // Перегружаем кабинет с новыми данными
                            header('Location: /lk');
                        }
                    } else {
                        $errors[] = 'Ошибка обновления данных.';
                    }
                }
            }

            if (isset($_POST['subscription'])) { // Подписка/Отписка на рассылку
                $subscription = (int) $_POST['subscription'] ?? 0;

                if (!(in_array($subscription, [0, 1]))) {
                    $errors[] = 'Некорректные данные. Обратитесь к администртору!';
                }

                if ($errors === false) { // Если ошибок нет, то обновляем данные пользователя.
                    if (Users::changeSubscription($_SESSION['user']['id'], $subscription)) { // Если обновление прошло нормально
                        // Получаем обновленные данные пользователя
                        $user = Users::getUserById($_SESSION['user']['id']);

                        if (!$user) {
                            // Если данные не получены - показываем ошибку
                            $errors[] = 'Ошибка получения данных.';
                        } else {
                            // Если данные правильные, запоминаем пользователя в сессии
                            Users::auth($user);

                            // Перегружаем кабинет с новыми данными
                            header('Location: /lk');

                            return new View('lk', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
                        }
                    } else {
                        $errors[] = 'Ошибка обновления данных.';
                    }
                }
            }

            return new View(
                'lk',
                [
                    'title' => Menu::showTitle(Menu::getUserMenu()),
                    'roles' => Roles::all(), // Роли пользователей
                    'name' =>  $name ?? '',
                    'email' =>  $email ?? '',
                    'subscription' => $subscription ?? '',
                    'aboutMe' => $aboutMe ?? '',
                    'errors' =>  $errors ?? '',

                ]
            ); // Вывод представления
        } else {
            header('Location: /login');
        }
    }

    /**
     * Вывод страницы для изменения пароля пользователя
     *
     * @return PasswordView- объект представления страницы изменения пароля пользователя
     */
    public function password()
    {
        if (isset($_SESSION['user']['id'])) {

            return new PasswordView('password', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
        } else {
            header('Location: /login');
        }
    }
}
