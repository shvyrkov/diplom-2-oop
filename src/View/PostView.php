<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Post;

/**
* Класс LkView — управление выводом view страницы.
*/
class PostView extends View
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
        $data = extract($this->data); // ['id' => 'id_article'] -> $id = 'id_article' - создается переменная для исп-я в html
        $menu = Menu::getUserMenu();
        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        $total = Post::all()->count(); // Всего товаров в БД
        $uri = $this->getURI(); // Получаем строку запроса без корня
        // $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
        $page = ($uri == 'post') ? 1 : preg_replace('~post/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел, то - 1
        $selected = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
        $page = $selected['page']; // Номер страницы

        if ($selected['limit'] == 'all' || $selected['limit'] > $total) {
            $limit = $total;
        } else {
            $limit = $selected['limit']; // Количество статей на странице в админке 
        }

        // Создаем объект Pagination - постраничная навигация - см.конструктор класса
        $pagination = new Pagination($total, $page, $limit, 'page-');

        // Статей для вывода на страницу
        $mails = Post::getMails($limit, $page);

// $mails = Post::all();

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else { // Если файл не найден
            throw new ApplicationException("$templateFile - шаблон не найден", 443); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}
