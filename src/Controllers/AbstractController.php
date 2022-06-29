<?php

namespace App\Controllers;

/**
 * Класс AbstractController - контроллер для утилит, используемых в приложении
 * @package App\Controllers
 */
class AbstractController
{
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
