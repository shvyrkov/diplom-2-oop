<?php

namespace App\Controllers;

use \SplFileInfo;
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
                $result = Users::changeSubscription($this->user->id, 0); // то отписываем его от рассылки

                if ($result) { // Для авторизованного пользователя обновляем данные в сессии
                    Users::auth(Users::getUserById($this->user->id));
                } else {
                    $errors[] = 'Ошибка. Обратитесь к администратору.';
                }
            } else {
                $email = $this->user->email; // подставляем email пользователя в поле формы.
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
        if ($this->user) { // Если пользователь авторизован
            if (!$this->user->subscription) { // и НЕ подписан, 
                $result = Users::changeSubscription($this->user->id, 1); // то подписываем его на рассылку

                if ($result) { // Для авторизованного пользователя обновляем данные в сессии
                    Users::auth(Users::getUserById($this->user->id));
                } else {
                    $errors[] = 'Ошибка. Обратитесь к администратору.';
                }
            } else {
                $email = $this->user->email; // подставляем email пользователя в поле формы.
                $errors[] = ' Вы уже подписаны на рассылку.';
            }
        }

        if (isset($_POST['subscribe'])) {
            $email = $_POST['email'] ?? '';

            $errors = UserValidator::subscribeValidate($email);

            if (!$errors) {
                $subscribeUser = Users::getUserByEmail($email);

                if ($subscribeUser) { // Есть такой email в БД
                    if ($subscribeUser->subscription) { // пользователь подписан
                        $errors['subscription'] = ' Вы уже подписаны на рассылку.';
                    } else {
                        $result = Users::changeSubscription($subscribeUser->id, 1);
                    }
                } else { // Нет такого email в БД
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

                $errors = UserValidator::lkValidate(
                    $name,
                    $email,
                    $aboutMe,
                    $this->user
                );

                if ($_FILES['myfile']['name'] != '') { // Проверка на наличие файла для загрузки
                    $types = include(CONFIG_DIR . IMAGE_TYPES);

                    $fileError = SimpleImage::imageFileValidation($types, FILE_SIZE, $_FILES);

                    if ($fileError) {
                        $errors['file'] = $fileError; // Если валидация не прошла, то добавляем её ошибки
                    }

                    if (!$errors) { // Загружаем файл на сервер

                        if ((DEFAULT_AVATAR != $this->user->avatar) // Если это не заставка 
                            && file_exists(AVATAR_STORAGE . $this->user->avatar)
                        ) {
                            unlink(AVATAR_STORAGE . $this->user->avatar); // то удаляем старый аватар на сервере 
                        }
                        // @TODO: загрузка файла на сервер - убрать в метод? - См. Админку, чтобы использовать один и тот же метод.
                        $myfile = new SplFileInfo($_FILES['myfile']['name']); // Загружаемое имя файла с расширением
                        $fileName = $name ? $name : $this->user->name; // Имя файла без расширения: новое, если было изменено, иначе - старое
                        $fileName = $fileName . '.' . $myfile->getExtension(); // Имя файла с расширением
                        $fileMoved = move_uploaded_file($_FILES['myfile']['tmp_name'], AVATAR_STORAGE . $fileName); // Загрузка файла на сервер

                        if (!$fileMoved) {
                            $errors['file']['LoadServerError'] = 'Файл ' . $fileName . ' не был загружен на сервер';
                        }
                    }
                }

                if (!$errors) {
                    Users::updateUser($this->user->id, $name, $email, $aboutMe, ($_FILES['myfile']['name'] != '') ? $fileName : $this->user->avatar);

                    Users::auth($this->user); // Если данные правильные, запоминаем пользователя в сессии

                    $this->redirect('/lk');
                }
            }

            return new View(
                'lk',
                [
                    'title' => Menu::showTitle(Menu::getUserMenu()),
                    'roles' => Roles::all(), // Роли пользователей
                    'user' =>  $this->user ?? '',
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
        if ($this->user) {
            if (isset($_POST['submit'])) {
                $oldPassword = $_POST['old_password'] ?? null;
                $newPassword = $_POST['new_password'] ?? null;
                $confirmPassword = $_POST['confirm_password'] ?? null;

                $errors = UserValidator::passwordValidate(
                    $oldPassword,
                    $newPassword,
                    $confirmPassword,
                    $this->user
                );

                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                if (!$errors) {
                    Users::changePassword($this->user->email, $passwordHash);

                    if (!Users::checkUserData($this->user->email, $newPassword)) {
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
                    'user' => $this->user ?? null,
                    'old_password' => $oldPassword ?? null,
                    'new_password' => $newPassword ?? null,
                    'confirm_password' => $confirmPassword ?? null,
                    'success' => $success ?? null,
                    'errors' => $errors ?? null,
                ]
            );
        } else {
            $this->redirect('/login');
        }
    }
}
