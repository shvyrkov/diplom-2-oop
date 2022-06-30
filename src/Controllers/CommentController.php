<?php

namespace App\Controllers;

use App\Model\Articles;
use App\Model\Comments;
use App\Validator\ArticleValidator;
use App\View\View;

/**
 * Класс CommentController - контроллер для работы с комментариями
 * @package App\Controllers
 */
class CommentController extends AbstractPrivateController
{
    /**
     * Добавление комментария.
     *
     * @var int $id - данные строки запроса - id-статьи в БД
     *
     * @return View - объект представления страницы выбранной статьи
     */
    public function addComment($id)
    {
        if (isset($_POST['loadComment'])) {
            $text = $_POST['text'] ?? null;

            $errors = ArticleValidator::validate($text);

            if (!$errors) {
                $commentAdded = Comments::addComment($text, $id, $this->user);

                if (!$commentAdded) {
                    $errors[] = 'Ошибка записи комментария. Обратитесь к администртору!';
                } else {
                    $this->redirect('/article/' . $id); // Перегружаем с новыми данными для предотвращения переотправки формы
                }
            }

            $article = Articles::getArticleById($id); // Статья для вывода на страницу

            return new View(
                'article',
                [
                    'article' => $article,
                    'title' => $article->title, // Название статьи для <title> и др.в вётстке
                    'comments' => Comments::getCommentsByArticleId($id),
                    'user' => $this->user,
                    'errors' =>  $errors ?? null
                ]
            );
        }
    }

    /**
     * Утверждение комментария.
     *
     * @var int $id - данные строки запроса - id-статьи в БД
     */
    public function approveComment(int $articleId, int $commentId)
    {
        if (isset($_POST['approve'])) { // 
            Comments::approveComment($commentId);
        }

        $this->redirect('/article/' . $articleId);
    }

    /**
     * Отклонение комментария.
     *
     * @var int $id - данные строки запроса - id-статьи в БД
     */
    public function denyComment(int $articleId, int $commentId)
    {
        if (isset($_POST['deny'])) {
            Comments::denyComment($commentId);
        }

        $this->redirect('/article/' . $articleId);
    }
}
