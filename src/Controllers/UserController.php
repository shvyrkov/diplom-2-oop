<?php

namespace App\Controllers;

use \SplFileInfo;
use App\Components\Helper;
use App\Components\Menu;
use App\Components\SimpleImage;
use App\Model\Users;
use App\Model\Roles;
use App\Validator\UserValidator;
use App\View\View;

/**
 * Класс UserController - контроллер для работы с пользователем
 * @package App\Controllers
 */
class UserController extends AbstractController
{
    /**
     * Вывод страницы авторизации пользователя
     *
     * @return View - объект представления страницы авторизации пользователя
     */
    public function login(): View
    {
        if (!$this->user) {
            if (isset($_POST['submit'])) {
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;

                $errors = UserValidator::loginValidate($email);

                if (!$errors) {
                    $user = Users::checkUserData($email, $password);

                    if ($user) {
                        Users::auth($user); // Если данные правильные, запоминаем пользователя в сессии

                        $this->redirect('/lk');
                    } else {
                        $errors['wrongData'] = 'Неправильные данные для входа.<br>
                                Возможно нажата клавиша CapsLock или несоответствующая раскладка клавиатуры';
                    }
                }
            }
        } else {
            $this->redirect('/lk');
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
    public function unsubscribe(): View
    {
        if ($this->user) { // Если пользователь авторизован 
            if ($this->user->subscription) { // и подписан, 
                $email = $this->user->email; // то подставляем его email в поле формы.
            } else {
                $errors[] = ' Вы не подписаны на рассылку.';
            }
        }

        if (isset($_POST['unsubscribe'])) {
            $email = $_POST['email'] ?? null;

            $errors = UserValidator::unsubscribeValidate($email);

            if (!$errors) {
                $unsubscribeUser = Users::getUserByEmail($email);

                if (!$unsubscribeUser->subscription) { // пользователь не подписан
                    $errors['subscription'] = ' Вы не подписаны на рассылку.';
                } else {
                    $result = Users::changeSubscription($unsubscribeUser->id, 0);

                    if ($result) {
                        if ($this->user) {
                            Users::auth(Users::getUserById($unsubscribeUser->id));
                        }
                    } else {
                        $errors[] = 'Ошибка обработки данных. Обратитесь к Администратору.';
                    }
                }
            }
        }

        return new View(
            'unsubscribe',
            [
                'title' => 'Отписка от рассылки',
                'email' =>  $email ?? null,
                'result' =>  $result ?? null,
                'errors' =>  $errors ?? null,
            ]
        );
    }

    /**
     * Подписка на рассылку
     * 
     * @return View - объект представления страницы подписки на рассылку
     */
    public function subscription(): View
    {
        if (isset($_POST['subscribe'])) {
            if ($this->user) { // Если пользователь авторизован, то подписываем его на рассылку (кнопка Пописаться на Главной видна только у неподписанных пользователей)
                $result = Users::changeSubscription($this->user->id, 1);

                if ($result) { // Для авторизоанного пользователя обновляем данные в сессии
                    Users::auth(Users::getUserById($this->user->id));
                } else {
                    $errors[] = 'Ошибка. Обратитесь к администратору.';
                }
            } else { // Если пользователь НЕавторизован, то переводим его на страницу подписки для ввода e-mail
                $email = $_POST['email'] ?? '';

                $errors = UserValidator::subscribeValidate($email);

                if (!$errors) {
                    $id = Users::insertGetId(
                        ['email' => $email, 'role' => NO_USER, 'subscription' => 1]
                    );

                    if (Users::getUserById($id)->subscription) {
                        $result = true;
                    } else {
                        $errors[] = 'Ошибка. Обратитесь к администратору.';
                    }
                }
            }
        }

        return new View(
            'subscription',
            [
                'title' => 'Подписка на рассылку',
                'email' =>  $email ?? null,
                'user' =>  $this->user ?? null,
                'result' =>  $result ?? null,
                'errors' =>  $errors ?? null,
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
        if (!$this->user) {
            if (isset($_POST['submit'])) {
                $name = $_POST['name'] ?? null;
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;
                $confirm_password = $_POST['confirm_password'] ?? null;
                $rules = isset($_POST['rules']) ? true : false;

                $errors = UserValidator::registrationValidate(
                    $name,
                    $email,
                    $password,
                    $confirm_password,
                    $rules
                );

                if (!$errors) {
                    Users::register($email, USER, $name, password_hash($password, PASSWORD_DEFAULT));

                    $user = Users::checkUserData($email, $password);

                    if (!$user) {
                        $errors[] = 'Регистрация не прошла.';
                    } else {
                        Users::auth($user); // Если данные правильные, запоминаем пользователя в сессии

                        $this->redirect('/lk');
                    }
                }
            }
        } else {
            $this->redirect('/lk');
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
        if ($this->user) {
            if (isset($_POST['submit'])) { // Обработка формы ЛК
                $name = $_POST['name'] ?? $this->user->name;
                $email = $_POST['email'] ?? $this->user->email;
                $aboutMe = $_POST['aboutMe'] ?? $this->user->aboutMe;

                $errors = null;
                // Проверка ошибок ввода
                if (!Users::checkName($name)) {
                    $errors['checkName'] = '- не должно быть короче ' . MIN_NAME_LENGTH . '-х символов';
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

                if (!Helper::checkLength($aboutMe, MIN_ABOUTME_LENGTH, MAX_ABOUTME_LENGTH)) {
                    $errors['checkAboutMe'] = 'не должно быть меньше ' . MIN_ABOUTME_LENGTH . ' и не больше ' . MAX_ABOUTME_LENGTH . ' символов';
                }

                if ($_FILES['myfile']['name'] != '') { // Проверка на наличие файла для загрузки
                    $types = include(CONFIG_DIR . IMAGE_TYPES);
                    $fileError = SimpleImage::imageFileValidation($types, FILE_SIZE, $_FILES); // Валидация файла изображения

                    if ($fileError) {
                        $errors['file'] = $fileError; // Если валидация не прошла, то добавляем её ошибки
                    }

                    if (!$errors) { // Загружаем файл на сервер

                        if ((DEFAULT_AVATAR != $_SESSION['user']['avatar']) // Если это не заставка 
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

                if (!$errors) { // Если ошибок нет, то обновляем данные пользователя.
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
                            $this->redirect('/lk');
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
                            $errors[] = 'Ошибка получения данных.';
                        } else {
                            Users::auth($user); // Если данные правильные, запоминаем пользователя в сессии

                            $this->redirect('/lk'); // Перегружаем кабинет с новыми данными
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
            $this->redirect('/login');
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
            $this->redirect('/login');
        }
    }
}
