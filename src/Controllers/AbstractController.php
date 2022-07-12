<?php

namespace App\Controllers;

use App\Components\Pagination;
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

    /**
     * Полечение данных для пагинации
     *
     * @var string $modelClass - класс модели для таблицы в БД
     * @var string $viewClass - класс представления
     * @var string $requiredURI - uri-страницы, на которой идет обработка
     * @var string $pattern - для правильной обработки строки запроса
     * 
     * @return array $paginationData[$total, $page, $limit, $selected]
     */
    public function getPaginationData(
        string $modelClass,
        string $viewClass,
        string $requiredURI,
        string $pattern
    ): array {

        $paginationData['total'] = $modelClass::all()->count(); // Всего пользователей в БД
        $uri = $viewClass::getURI() ?? ''; // Получаем строку запроса без корня
        $page = ($uri == $requiredURI) ? 1 : preg_replace($pattern, '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел /admin-users, то - 1
        $paginationData['selected'] = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
        $paginationData['page'] = $paginationData['selected']['page']; // Номер страницы

        if ($paginationData['selected']['limit'] == 'all' || $paginationData['selected']['limit'] > $paginationData['total']) {
            $paginationData['limit'] = $paginationData['total'];
        } else {
            $paginationData['limit'] = $paginationData['selected']['limit']; // Количество статей на странице в админке 
        }

        return $paginationData;
    }
}
