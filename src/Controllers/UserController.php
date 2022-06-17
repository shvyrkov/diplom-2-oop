<?php

namespace App\Controllers;

use \SplFileInfo;
use App\Components\Menu;
use App\Components\SimpleImage;
use App\Model\Users;
use App\Model\Roles;
use App\View\View;

/**
 * Класс UserController - контроллер для работы с пользователем
 * @package App\Controllers
 */
class UserController
{
    /**
     * Вывод страницы авторизации пользователя
     *
     * @return object View - объект представления страницы авторизации пользователя
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
        return new View('exit', ['title' => 'Выход']); // Вывод представления
    }

    /**
     * Отписка от рассылки.
     *
     * @return View - объект представления страницы отписки от рассылки.
     */
    public function unsubscribe()
    {
        $result = false;
        $errors = false;
        $email = ''; // Для неавторизованных пользователей

        if (isset($_SESSION['user']['email'])) { // Если пользователь авторизован 
            if ($_SESSION['user']['subscription']) { // и подписан, то подставляем его email.
                $email = $_SESSION['user']['email'];
            } else {
                $errors[] = ' Вы не подписаны на рассылку.';
            }
        }

        if (isset($_POST['unsubscribe'])) {
            if (isset($_POST['email'])) { // Берем email из формы
                $email = $_POST['email'];
            }
            // Валидация e-mail
            if (!$email) {
                $errors[] = 'Введите e-mail';
            }

            if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                $errors['checkEmail'] = ' Неправильный email';
            } elseif (Users::checkEmailExists($email)) { //  Если есть пользователь с таким e-mail
                $user = Users::getUserByEmail($email);

                if (!$user->subscription) { // и он не подписан
                    $errors['checkEmailExists'] = ' Вы не подписаны на рассылку.';
                }
            } else {
                $errors['checkEmailExists'] = ' Вы не подписаны на рассылку.';
            }

            if (!$errors) {
                if ($user) { // Если пользователь с таким e-mail есть, то
                    $result = Users::changeSubscription($user->id, 0); // отписываем его от рассылки.

                    if ($result) {
                        $_SESSION['user']['subscription'] = 0; // Обновляем данные в сессии
                    } else {
                        $errors[] = 'Ошибка обработки данных. Обратитесь к Администратору.';
                    }
                } else { // если нет, то выдаем ошибку
                    $errors[] = 'Ошибка получения данных. Обратитесь к Администратору.';
                }
            }
        }

        return new View(
            'unsubscribe',
            [
                'title' => 'Отписка от рассылки',
                'result' =>  $result ?? '',
                'email' =>  $email ?? '',
                'errors' =>  $errors ?? '',
            ]
        );
    }

    /**
     * Подписка на рассылку
     * 
     * @return object View - объект представления страницы подписки на рассылку
     */
    public function subscription()
    {
        $result = false;
        $errors = false;
        $user = '';
        $id = $_SESSION['user']['id'] ?? '';

        if (isset($_POST['subscribeAuthUser'])) {
            if ($id) { // Если пользователь авторизован, то подписываем его на рассылку (кнопка Пописаться видна только у неподписанных пользователей)
                $result = Users::changeSubscription($id, 1);

                if ($result) { // Только для авторизоанного пользователя запрашиваем новые данные 
                    $user = Users::getUserById($id);
                }

                if ($user) { // Если данные правильные, запоминаем пользователя в сессии 
                    Users::auth($user);
                } else { // Если данные не получены - показываем ошибку 
                    $errors[] = 'Ошибка получения данных.';
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

            if (Users::checkEmailExists($email)) { //  Есть пользователь
                $user = Users::getUserByEmail($email);

                if ($user->name) { // уже зарегистрированный 
                    $errors['checkEmailExists'] = ' Такой email уже используется, авторизуйтесь пожалуйста.';
                } elseif ($user->subscription) { // НЕавторизованный, но подписанный пользователь
                    $errors['checkEmailExists'] = ' Вы уже подписаны на рассылку.';
                }
            }

            if (!$errors) { // Если ошибок нет, то подписываем пользователя на рассылку.
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

        return new View(
            'subscription',
            [
                'title' => 'Подписка на рассылку',
                'result' => $result ?? '',
                'email' => $email ?? '',
                'errors' => $errors ?? '',
            ]
        );
    }

    /**
     * Вывод страницы регистрации пользователя
     *
     * @return View - объект представления страницы регистрации нового пользователя
     */
    public function registration()
    {
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

            if (!$errors) { // Если ошибок нет, то регистрируем пользователя.
                Users::register($email, USER, $name, $passwordHash);

                // Проверяем зарегистрировался ли пользователь
                $user = Users::checkUserData($email, $password);

                if (!$user) {
                    // Если данные неправильные - показываем ошибку
                    $errors[] = 'Регистрация не прошла.';
                    // Если регистрация на прошла - опять на страницу регистрациии
                } else {
                    // Если данные правильные, запоминаем пользователя в сессии
                    Users::auth($user);

                    // Перенаправляем пользователя в закрытую часть - кабинет 
                    header('Location: /lk');
                }
            }
        }

        return new View(
            'registration',
            [
                'title' => 'Регистрация',
                'name' => $name ?? '',
                'email' => $email ?? '',
                'password' => $password ?? '',
                'confirm_password' => $confirm_password ?? '',
                'rules' => $rules ?? '',
                'errors' => $errors ?? '',
            ]
        );
    }

    /**
     * Вывод страницы личного кабинета пользователя
     *
     * @return View - объект представления страницы личного кабинета пользователя
     */
    public function lk()
    {
        if (isset($_SESSION['user']['id'])) {
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
            );
        } else {
            header('Location: /login');
        }
    }

    /**
     * Вывод страницы для изменения пароля пользователя
     *
     * @return View- объект представления страницы изменения пароля пользователя
     */
    public function password()
    {
        if (isset($_SESSION['user']['id'])) { // Пользователь авторизован
            $email = $_SESSION['user']['email'];
            $errors = false;
            $success = '';
            $user = '';

            if (isset($_POST['submit'])) { // Обработка формы авторизации
                $old_password = $_POST['old_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';

                if (!($old_password && $new_password && $confirm_password)) {
                    $errors[] = 'Не все поля заполнены';
                }
                // Проверка ошибок ввода
                if (!Users::checkUserData($email, $old_password)) { // Check current password
                    $errors[] = 'Неправильный пароль.';
                }

                if (!Users::checkPassword($new_password)) {
                    $errors['checkPassword'] = 'Пароль не должен быть короче 6-ти символов';
                }

                if (!Users::comparePasswords($new_password, $confirm_password)) {
                    $errors['comparePasswords'] = 'Пароли не совпадают';
                }

                $passwordHash = password_hash($new_password, PASSWORD_DEFAULT);

                if (!$errors) { // Если ошибок нет, то меняем пароль.
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

            return new View(
                'password',
                [
                    'title' =>  'Изменение пароля',
                    'email' => $email ?? '',
                    'old_password' => $old_password ?? '',
                    'new_password' => $new_password ?? '',
                    'confirm_password' => $confirm_password ?? '',
                    'success' => $success ?? '',
                    'errors' => $errors ?? '',
                ]
            );
        } else { // Если пользователь неавторизован, то предлагаем авторизоваться
            header('Location: /login');
        }
    }
}
