<?php

namespace App\Controllers;

use App\View\ArticleView;

class ArticleController
{
    /**
     * Вывод страницы выбранной статьи
     *
     * @var string $id - данные строки запроса - id-статьи
     *
     * @return ArticleView - объект представления страницы выбранной статьи
     */
    public function article($id)
    {
        return new ArticleView('article', ['id' => $id]);
    }
}
