<?php

namespace App\View;

/**
* Интерфейс App\View\Renderable используется для классов, которые сами отвечают за вывод необходимой строки.
*/
interface Renderable
{
    /**
    * Метод выводит необходимый шаблон.
    */
    public function render();
}
