<?php
namespace App\Controllers;

use App\View\View;
use App\Components\Menu;

/**
 * Класс UserController - контроллер для работы с пользователями
 */
class UserController
{
    /**
     * Регистрация пользователя - создание записи в БД
     *
     * @return bool in case of successful operation
     */ 
    public function registration()
    {
        // Инициализация переменных, чтобы не было ошибок
        $name = '';
        $email = '';
        $password = '';
        $password_1 = '';
        $password_2 = '';
        //$code_sent = ''; // Код для проверки e-mail
        $result = false;
        
        if (isset($_POST['submit'])) { // Ввод регистрационных данных
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password_1 = $_POST['password_1'];
            $password_2 = $_POST['password_2'];
            
            $errors = false; // Для хранения ошибок неправильного ввода
            
        // Проверка ошибок ввода
            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            
            if (!User::checkEmail($email)) { //  Проверка правильности ввода e-mail
                $errors[] = 'Неправильный email';
            }
            
            if (!User::checkPassword($password_1)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            if (!User::comparePasswords($password_1, $password_2)) {
                $errors[] = 'Пароли не совпадают';
            }
            
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            
        // Если ошибок нет, то переходим на страницу подтверждения e-mail пользователя.
            if ($errors == false) {
                //  Отсылка кода на e-mail пользователя
                $code_sent = User::validateUserEmail($email);

                // Запоминаем данные пользователя в сессии
                $_SESSION['name'] = $name; 
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password_1;
                $_SESSION['code_sent'] = $code_sent;
                
        // Переход на страницу подтверждения e-mail
                require_once(ROOT . '/views/user/email_confirmation.php'); 
            } else {
        // Если ошибки есть, то опять на страницу регистрации с выводом ошибок.
                require_once(ROOT . '/views/user/register.php');
            }

        } // submit
        
        if (isset($_POST['submit_email'])) { // Подтверждение e-mail
            
            $code_sent = $_SESSION['code_sent']; // Код отправленный пользователю
            $email_confirmation = $_POST['email_confirmation']; // Код введенный пользователем
            
            $errors = false; // Для хранения ошибок неправильного ввода

    // Проверка совпадения кодов
            if ($email_confirmation != $code_sent) {
                $errors[] = 'Неверный код';
        // Если ошибки есть, то опять на страницу ввода кода подтверждения с выводом ошибок.
                require_once(ROOT . '/views/user/email_confirmation.php');
            }
            
    // Если ошибок нет, то регистрируем пользователя.
            if ($errors == false) { 
                
                $name = $_SESSION['name']; 
                $email = $_SESSION['email'];
                $password = $_SESSION['password'];
            
                $result = User::register($name, $email, $password); 
            
            // Проверяем зарегистрировался ли пользователь
                $user = User::checkUserData($email, $password);
                
                if ($user == false) {
                    // Если данные неправильные - показываем ошибку
                    $errors[] = 'Регистрация не прошла.';
                    // Если регистрация на прошла - опять на страницу регистрациии
                } else {
                    // Если данные правильные, запоминаем пользователя в сессии
                    User::auth($user);
                    
                    // Удаляем суперглобальные переменные после удачной регистрации
                    unset ($_SESSION['name']);
                    unset ($_SESSION['email']);
                    unset ($_SESSION['password']);
                    unset ($_SESSION['code_sent']);
                }
            }
        } // submit_email

        require_once(ROOT . '/views/user/register.php');

        return true; 
    }
    
    /**
     * Авторизация пользователя (вход  в личный кабинет)
     *
     * @return bool in case of successful operation
     */ 
    public function actionLogin()
    {
        $email = '';
        $password = '';
        
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $errors = false;
                        
            // Валидация полей
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }            

            // Проверяем существует ли пользователь
            $user = User::checkUserData($email, $password);
            
            if ($user == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт.<br>
                            Возможно нажата клавиша CapsLock или несоответствующая раскладка клавиатуры';
            } else {
                // Если данные правильные, запоминаем пользователя (сессия)
                User::auth($user);
                
                // Перенаправляем пользователя в закрытую часть - кабинет 
                header('Location: /'.HOME.'/cabinet/');
            }

        }

        require_once(ROOT . '/views/user/login.php');

        return true;
    }
    
    /**
     * Выход из аккаунта - удаление данных о пользователе из сессии
     */
    public function actionLogout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['user_name']);
        header('Location: /'.HOME.'/'); // Перенаправление на главную страницу сайта 
    }
    
} // Class
