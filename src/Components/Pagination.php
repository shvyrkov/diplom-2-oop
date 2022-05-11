<?php

namespace App\Components;

/*
 * Класс для генерации постраничной навигации
 */

class Pagination
{

    /** 
     * @var Количество ссылок навигации на страницу
     */
    private $max = 10;

    /**
     * @var Ключ для GET, в который пишется номер страницы
     *      у нас - 'page-'
     */
    private $index = 'page';

    /**
     * @var Номер текущей страницы
     */
    private $current_page;

    /**
     * @var Общее количество записей
     */
    private $total;

    /**
     * @var Количество записей на страницу
     */
    private $limit;

    /**
     * Конструктор - загрузка необходимых данных для навигации
     * 
     * @param integer $total - общее количество записей
     * @param integer $currentPage - Номер текущей страницы
     * @param integer $limit - количество записей на страницу
     * @param string $index - Ключ для GET, в который пишется номер страницы 
     */
    public function __construct($total, $currentPage, $limit, $index)
    {
        // Устанавливаем общее количество записей
        $this->total = $total;

        // Устанавливаем количество записей на страницу
        $this->limit = $limit;

        // Устанавливаем ключ в url
        $this->index = $index;

        // Устанавливаем количество страниц
        $this->amount = $this->amount();

        // Устанавливаем номер текущей страницы
        $this->setCurrentPage($currentPage);
    }

    /**
     *  Для вывода ссылок (генератор ссылок на странице)
     * 
     * @return HTML-код со ссылками навигации
     */
    public function get()
    {
        // Для записи ссылок
        $links = null;

        // Получаем ограничения для цикла
        $limits = $this->limits();
// var_dump($limits);
        $html = '<ul class="pagination justify-content-end">';
        // Генерируем ссылки
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {
// echo "<br>";
// var_dump($page);
            // Если текущая это текущая страница, ссылки нет и добавляется класс active
            if ($page == $this->current_page) {
                $links .= '<li class="page-item active"><a class="page-link" href="#">' . $page . '</a></li>';
// echo "<br>";
// var_dump($links);
            } else {
                // Иначе генерируем ссылку
                $links .= $this->generateHtml($page);
            }
        }

        // Если ссылки создались
        if (!is_null($links)) {
            
            // Если текущая страница не первая
            if ($this->current_page > 1) {
                // Ссылка на предыдущую страницу
                $links = $this->generateHtml($this->current_page - 1, '&lt;'). $links;
                // Создаём ссылку "На первую страницу"
                $links = $this->generateHtml(1, '&lt;&lt;') . $links;
            }
                
            // Если текущая страница не первая
            if ($this->current_page < $this->amount) {
                // Ссылка на последующую страницу
                $links .= $this->generateHtml($this->current_page + 1, '&gt;');
                // Создаём ссылку "На последнюю страницу"
                $links .= $this->generateHtml($this->amount, '&gt;&gt');
            }
        }

        $html .= $links . '</ul>';

        // Возвращаем html
        return $html;
    }

    /**
     * Для генерации HTML-кода ссылки
     * 
     * @param integer $page - номер страницы
     * @param string $text - текст ссылки
     * 
     * @return string - код ссылки
     */
    private function generateHtml($page, $text = null)
    {
        // Если текст ссылки не указан
        if (!$text)
        // Указываем, что текст - цифра страницы
            $text = $page;

        $currentURI = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
// echo "currentURI: " . $currentURI . '<br>';
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);
// echo '$currentURI . $this->index . $page :' . $currentURI . $this->index . $page . ' - text: ' . $text;
// echo "<br>";
        // Формируем HTML код ссылки и возвращаем
        return
                '<li class="page-item"><a class="page-link" href="' . $currentURI . $this->index . $page . '">' . $text . '</a></li>';
    }

    /**
     *  Для получения, откуда стартовать
     * 
     * @return array - массив с началом и концом отсчёта
     */
    private function limits()
    {
        // Вычисляем ссылки слева (чтобы активная ссылка была посередине)
        $left = $this->current_page - round($this->max / 2);

        // Вычисляем начало отсчёта
        $start = $left > 0 ? $left : 1;

        // Если впереди есть как минимум $this->max страниц
        if ($start + $this->max <= $this->amount)
        // Назначаем конец цикла вперёд на $this->max страниц или просто на минимум
            $end = $start > 1 ? $start + $this->max : $this->max;
        else {
            // Конец - общее количество страниц
            $end = $this->amount;
            // Начало - минус $this->max от конца
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }
        // Возвращаем
        return array($start, $end);
    }

    /**
     * Для установки текущей страницы
     * 
     * @param int $currentPage - номер текущей страницы
     * 
     */
    private function setCurrentPage($currentPage)
    {
        // Получаем номер страницы
        $this->current_page = $currentPage;

        // Если текущая страница боле нуля
        if ($this->current_page > 0) {
            // Если текунщая страница меньше общего количества страниц
            if ($this->current_page > $this->amount)
            // Устанавливаем страницу на последнюю
                $this->current_page = $this->amount;
        } else
        # Устанавливаем страницу на первую
            $this->current_page = 1;
    }

    /**
     * Для получеия общего числа страниц
     * 
     * @return int число страниц
     */
    private function amount()
    {
        // Делим и возвращаем
        //return round($this->total / $this->limit); // Было - КОСЯК !!! 
        // Округление в большую (!!!) сторону до целого числа
        return ceil($this->total / $this->limit);
    }
    
/**
 * Настройка количества товаров на странице
 * 
 * @BUG: не проставляет 'selected' в составе контроллера
 * 
 * @TODO: передать метод в Components
 * 
 */
    public static function goodsQuantity($page)
    {
        // Массив для return
        $selected = array();
        
        $selected['limit'] = $_SESSION['limit'] ?? 4; // default value
        $selected['page'] = $page;
        
        if (isset($_POST['itemsOnPageHeader']) ) {
            $selected['limit'] = $_POST['itemsOnPageHeader']; // Получаем значение с верхнего 'select'
        //echo '$_POST[\'itemsOnPageHeader\']: '.$_POST['itemsOnPageHeader'].'<br>';
            $_SESSION['limit'] = $selected['limit'];
            $selected['page'] = 1;
        }
        
        if (isset($_POST['itemsOnPageFooter'])) {
            $selected['limit'] = $_POST['itemsOnPageFooter']; // Получаем значение с нижнего 'select'
        //echo '$_POST[\'itemsOnPageFooter\']: '.$_POST['itemsOnPageFooter'].'<br>';
            $_SESSION['limit'] = $selected['limit'];
            $selected['page'] = 1;
        }

        // Устанавливаем атрибут 'selected' для 'option' в 'select'
        if($selected['limit'] == 4) {
            $selected['4'] = ' selected ';
        } else {
            $selected['4'] = '';
        }
        if($selected['limit'] == 8) {
            $selected['8'] = ' selected ';
        } else {
            $selected['8'] = '';
        }
        if($selected['limit'] == 12) {
            $selected['12'] = ' selected ';
        } else {
            $selected['12'] = '';
        }
        
        return $selected; //
    }
}
