<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Users;
use App\Model\Articles;


/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class AdminArticlesView extends AdminView
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
     /** метод должен выводить необходимый шаблон. Внутри метода данные свойства $data распаковать в переменные через extract(), а затем подключить страницу шаблона, получив полный путь к ней с помощью другого метода этого класса getIncludeTemplate().
    */

        $uri = $this->getURI(); // Получаем строку запроса без корня
        $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
        $selected = Pagination::goodsQuantity($page);
        // // $limit = $selected['limit']; // Количество товаров на странице по умолчанию (константа в класса Pagination или из представления)
        $limit = Articles::getArticlesQtyOnPage(); // Количество товаров на странице
        $page = $selected['page']; // Номер страницы
        $total = Articles::all()->count(); // Всего товаров в БД

        // Статей для вывода на страницу
        $articles = Articles::getArticles($limit, $page);
// $articles = Articles::getArticles();
        // $articles = Articles::all();

        // Создаем объект Pagination - постраничная навигация - см.конструктор класса
        // $pagination = new Pagination($total, $page, $limit, 'page-');


        extract($this->data); // ['title' => 'Index Page'] -> $title = 'Index Page' - создается переменная для исп-я в html
        $menu = Menu::getAdminMenu();

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (isset($_POST['exit'])) {
            Users::exit();
        }

            if (file_exists($templateFile)) {
                include $templateFile; // Вывод представления
            } else { // Если файл не найден
                throw new ApplicationException("$templateFile - шаблон не найден", 442); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
            }
    }
}
