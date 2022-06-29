<?php

namespace App\Controllers;

use App\Model\Articles;
use App\Model\Comments;
use App\Model\Methods;
use App\Validator\ArticleValidator;
use App\View\View;

/**
 * Класс ArticleController - контроллер для работы со статьями
 * @package App\Controllers
 */
class ArticleController extends AbstractPrivateController
{
    public function addComment($id)
    {
        if (isset($_POST['loadComment'])) { // Обработка формы добавления комментария
            $text = $_POST['text'] ?? null;
            // $errors = null;

            if (!$this->user) {
                $errors[] = 'Авторизуйтесь пожалуйста.';
            } else {
                $errors = ArticleValidator::validate($text);
            }

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
                    'id' => $id,
                    'article' => $article,
                    'title' => $article->title, // Название статьи для <title> и др.в вётстке
                    'comments' => Comments::getCommentsByArticleId($id), // Комментарии к статье
                    'user' => $this->user,
                    'errors' =>  $errors ?? ''
                ]
            );
        }
    }
    /**
     * Вывод страницы выбранной статьи
     *
     * @var string $id - данные строки запроса - id-статьи в БД
     *
     * @return View - объект представления страницы выбранной статьи
     */
    public function showArticle($id)
    {
        if (isset($_POST['approve'])) { // Утверждение комментария.
            Comments::approveComment($_POST['approve']);
        }

        if (isset($_POST['deny'])) { // Отклонение (удаление) комментария.
            Comments::denyComment($_POST['deny']);
        }

        $article = Articles::getArticleById($id); // Статья для вывода на страницу

        return new View(
            'article',
            [
                'id' => $id,
                'article' => $article,
                'title' => $article->title, // Название статьи для <title>
                'comments' => Comments::getCommentsByArticleId($id), // Комментарии к статье
                'user' => $this->user,
                'errors' =>  $errors ?? ''
            ]
        );
    }

    /**
     * Вывод статей по типу метода
     *  
     * @return object View - объект представления вывода статей по типу метода
     */
    public function method()
    {
        $method = Methods::getMethodByURI(View::getURI()); // Получаем тип метода из строки запроса

        $data = [
            'title' => $method->name,  // Название типа метода для <title>
            'method' => $method,
            'articles' => Articles::getArticlesByMethod($method->id) // Получаем статьи по типу метода
        ];

        return new View('method', $data); // Вывод представления
    }
}
