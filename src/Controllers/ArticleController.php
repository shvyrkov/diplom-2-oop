<?php

namespace App\Controllers;

use App\View\View;
use App\Model\Articles;
use App\Model\Comments;
use App\Model\Methods;

/**
 * Класс ArticleController - контроллер для работы со статьями
 * @package App\Controllers
 */
class ArticleController extends AbstractPrivateController
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
        if (isset($_POST['loadComment'])) { // Обработка формы добавления комментария
            $text = $_POST['text'] ?? '';
            $articleId = $_POST['articleId'] ?? '';
            $userId = $_POST['userId'] ?? ''; // Уязвимость - см.AbstractPrivateController
            $role = $_POST['role'] ?? '';

            $errors = false;

            // Валидация полей
            if (!$userId) {
                $errors[] = 'Авторизуйтесь пожалуйста.';
            } elseif (!(is_numeric($articleId) && is_numeric($userId) && is_numeric($role))) { // Индексы д.б.целыми числами.
                $errors[] = 'Некорректные данные. Обратитесь к администртору!';
            } elseif ($userId != $_SESSION['user']['id']) { // Подтверждение, что это тот пользователь, который залогинился.
                $errors[] = 'Неавторизованный пользователь. Обратитесь к администртору!';
            } elseif ($role != $_SESSION['user']['role']) { // Подтверждение роли пользователь, который залогинился.
                $errors[] = 'Некорректная роль пользователя. Обратитесь к администртору!';
            } elseif ($articleId != $id) { // Индекс статьи не был изменен в средствах разработчика.
                $errors[] = 'Ошибка данных статьи. Обратитесь к администртору!';
            } elseif (strlen($text) >= MAX_COMMENT_LENGTH) {
                $errors[] = 'Длина комментария ' . strlen($text) . ' байт, что больше допустимой в ' . MAX_COMMENT_LENGTH . ' байт';
            } elseif (empty($text)) {
                $errors[] = 'Внесите комментарий';
            }

            if (!$errors) {
                // Если данные правильные, вносим комментарий в БД
                $commentAdded = Comments::addComment($text, $articleId, $userId, $role);

                if (!$commentAdded) {
                    $errors[] = 'Ошибка записи комментария. Обратитесь к администртору!';
                } else {
                    header('Location: /article/' . $articleId); // Перегружаем с новыми данными для предотвращения переотправки формы
                }
            }
        }

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
