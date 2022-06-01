<?php

namespace App\Controllers;

use App\View\View;
use App\Components\Menu;
use App\View\LoginView;
use App\View\RegistrationView;
use App\View\LkView;
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
        return new LoginView('login', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
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
     * @return LkView - объект представления страницы личного кабинета пользователя
     */
    public function lk()
    {
        if (isset($_SESSION['user']['id'])) {

            return new LkView('lk', ['title' => Menu::showTitle(Menu::getUserMenu())]); // Вывод представления
        } else {
            header('Location: /login'); // @TODO: Выводить текст: вы не авторизованы...?
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
            header('Location: /login'); // @TODO: Выводить текст: вы не авторизованы...?
        }
    }

    /**
     * @TEST: Метод принимает значения $params из строки запроса и выдает их обратно в виде строки опред-го вида...
     *
     */
    public function test(...$params)
    {
        $string = "Test Page With : ";
        $i = 1;

        foreach ($params as $param) {
            $string .= ' param_' . $i++ . ' = ' . $param;
        }
        // echo "<pre>";
        // echo "<br>_POST:<br>";
        // var_dump($_POST);
        // echo "<br>_GET:<br>";
        // var_dump($_GET);
        // echo "<br>SERVER:<br>";
        // var_dump($_SERVER);
        // echo "</pre>";

        return $string;
    }

    /**
     * @TEST: Метод принимает значения $params из строки запроса и выдает их обратно в виде строки опред-го вида...
     *
     */
    public function index(...$params)
    {
        $params = [ // ???

            'title' => 'Главная', // Название пункта меню
            'path' => '/', // Ссылка на страницу, куда ведет этот пункт меню
            'class' => HomeController::class, // ?
            'method' => 'index', // ?
            'sort' => 0, // Индекс сортировки (?)

        ];

        return new View($params['path'], [
            'title' => 'Контакты',
            'link_1' => '/', 'linkText_1' => 'На главную',
            'link_2' => '/about', 'linkText_2' => 'О нас',
            'link_3' => '/post', 'linkText_3' => 'Почта'
        ]); // Вывод представления
    }
}
