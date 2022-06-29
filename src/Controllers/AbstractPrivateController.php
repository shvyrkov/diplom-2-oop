<?php

namespace App\Controllers;

use App\Model\Users;

/**
 * Класс AbstractPrivateController - контроллер для получения объекта с данными авторизованного пользователя
 * @package App\Controllers
 */
class AbstractPrivateController extends AbstractController
{
    /**
     * Объект с данными авторизованного пользователя.
     *
     * @var object
     */
    protected $user;

    public function __construct()
    {
        $this->user = Users::getUserById($_SESSION['user']['id'] ?? 0);

        // if (!$this->user) { // если пользователь не найден, то редирект на авторизацию
        //     $this->redirect('/login'); // Это нужно только, если это вход в ЛК или для админки
        // }
    }
}
