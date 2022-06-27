<?php

namespace App\Controllers;

use App\Model\Users;

/**
 * Класс AbstractPrivateController - контроллер для обработки данных авторизованных пользователей
 * @package App\Controllers
 */
class AbstractPrivateController extends AbstractController
{
    protected $user;

    public function __construct()
    {
        $this->user = Users::getUserById($_SESSION['user']['id']);

        if (!$this->user) { // если пользователь не найден, то редирект на авторизацию
            $this->redirect('/login');
        }
    }
}
