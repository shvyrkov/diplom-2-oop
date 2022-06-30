<?php

namespace App\Controllers;

use App\Model\Users;

/**
 * Класс AbstractController - контроллер для утилит, используемых в приложении
 * @package App\Controllers
 */
class AbstractController
{
    /**
     * Объект с данными авторизованного пользователя или null.
     *
     * @var object
     */
    protected $user;

    public function __construct()
    {
        $this->user = Users::getUserById($_SESSION['user']['id'] ?? 0);
    }

    /**
     * Переход на заданную страницу
     *
     * @var string $url - url-страницы
     */
    public function redirect(string $url)
    {
        header('Location: ' . $url);
        die();
    }
}
