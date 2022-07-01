<?php

namespace App\Controllers;

use App\Model\Articles;
use App\Model\Comments;
use App\Model\Methods;
use App\View\View;

/**
 * Класс ArticleController - контроллер для работы со статьями
 * @package App\Controllers
 */
class ArticleController extends AbstractController
{
    /**
     * Вывод страницы выбранной статьи
     *
     * @var int $id - данные строки запроса - id-статьи в БД
     *
     * @return View - объект представления страницы выбранной статьи
     */
    public function showArticle(int $id): View
    {
        $article = Articles::getArticleById($id); // Статья для вывода на страницу

        return new View(
            'article',
            [
                'article' => $article,
                'title' => $article->title, // Название статьи для <title> и пр. в вёрстке
                'comments' => Comments::getCommentsByArticleId($id),
                'user' => $this->user,
            ]
        );
    }

    /**
     * Вывод статей по типу метода
     *  
     * @return View - объект представления вывода статей по типу метода
     */
    public function showArticlesByMethod(): View
    {
        $method = Methods::getMethodByURI(View::getURI());

        return new View(
            'method',
            [
                'title' => $method->name, // Название типа метода для <title>
                'method' => $method,
                'articles' => Articles::getArticlesByMethod($method->id)
            ]
        );
    }
}
