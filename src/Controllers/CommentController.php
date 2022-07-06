<?php

namespace App\Controllers;

use App\Model\Articles;
use App\Model\Comments;
use App\Validator\CommentValidator;
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
    public function addComment(int $id)
    {
        if (isset($_POST['loadComment'])) {
            $text = $_POST['text'] ?? null;

            $errors = CommentValidator::validate($text);

            if (!$errors) {
                if (!Comments::addComment($text, $id, $this->user)) {
                    $errors[] = 'Ошибка записи комментария. Обратитесь к администртору!';
                } else {
                    $this->redirect('/article/' . $id); // Предотвращает переотправку формы.
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
                    'text' => $text,
                    'errors' =>  $errors ?? null
                ]
            );
        }
    }

    /**
     * Утверждение комментария.
     *
     * @var int $articleId - данные строки запроса - id-статьи в БД
     * @var int $commentId - данные строки запроса - id-комментария к статье в БД
     */
    public function approveComment(int $articleId, int $commentId)
    {
        if (isset($_POST['approve'])) {
            Comments::approveComment($commentId);
        }

        $this->redirect('/article/' . $articleId);
    }

    /**
     * Отклонение комментария.
     *
     * @var int $articleId - данные строки запроса - id-статьи в БД
     * @var int $commentId - данные строки запроса - id-комментария к статье в БД
     */
    public function denyComment(int $articleId, int $commentId)
    {
        if (isset($_POST['deny'])) {
            Comments::denyComment($commentId);
        }

        $this->redirect('/article/' . $articleId);
    }
}
