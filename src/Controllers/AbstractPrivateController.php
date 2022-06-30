<?php

namespace App\Controllers;

/**
 * Класс AbstractPrivateController - контроллер для обеспечения работы только авторизованного пользователя
 * @package App\Controllers
 */
class AbstractPrivateController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->user) {

            $this->redirect('/login');
        }
    }
}
