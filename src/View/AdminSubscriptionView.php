<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Components\Pagination;
use App\Model\Users;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class AdminSubscriptionView extends AdminView
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
     /** метод должен выводить необходимый шаблон. Внутри метода данные свойства $data распаковать в переменные через extract(), а затем подключить страницу шаблона, получив полный путь к ней с помощью другого метода этого класса getIncludeTemplate().
    */

        $userId = '';
        $subscription = '';
        $errors = false;

        if (isset($_POST['submit'])) { // Измененине роли пользователя
            $userId = $_POST['id'];
            $subscription = $_POST['subscription'] ?? 0;

            // Валидация полей
            if (!$userId) {
                $errors[] = 'Авторизуйтесь пожалуйста.';
            } elseif (!(is_numeric($userId) && in_array($subscription, [0, 1]))) { // Индексы д.б.целыми числами.
                $errors[] = 'Некорректные данные. Обратитесь к администртору!';
            } else {
                Users::changeSubscription($userId, $subscription);
            }
        }

        $total = Users::all()->count(); // Всего товаров в БД
        $uri = $this->getURI(); // Получаем строку запроса без корня
        // $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
        $page = ($uri == 'admin-subscription') ? 1 : preg_replace('~admin-subscription/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел /admin-articles, то - 1
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
        $users = Users::getUsers($limit, $page);

        if (isset($_POST['exit'])) { // Выход пользователя из сессии.
            Users::exit();
        }

        extract($this->data); // ['title' => 'Index Page'] -> $title = 'Index Page' - создается переменная для исп-я в html
        $menu = Menu::getAdminMenu();

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else { // Если файл не найден
            throw new ApplicationException("$templateFile - шаблон не найден", 442); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}
