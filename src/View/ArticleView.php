<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Articles;
use App\Model\Comments;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class ArticleView extends View
{

    /**
    * Метод выводит необходимый шаблон.
    */
    public function render()
    {
     /** метод должен выводить необходимый шаблон. Внутри метода данные свойства $data распаковать в переменные через extract(), а затем подключить страницу шаблона, получив полный путь к ней с помощью другого метода этого класса getIncludeTemplate().
    */
        extract($this->data); // ['id' => 'id_article'] -> $id = 'id_article' - создается переменная для исп-я в html
        $menu = Menu::getUserMenu();

        $text = '';
        $articleId = '';
        $userId = '';
        $role = '';
        
        if (isset($_POST['loadComment'])) { // Обработка формы авторизации
            $text = $_POST['text'];
            $articleId = $_POST['articleId'];
            $userId = $_POST['userId'];
            $role = $_POST['role'];

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
                    header("Location: /article/$articleId"); // Если комментарий добавлен, то обновляем страницу, чтобы не допустить переотправку формы через обновление страницы.
                }
            }
        }

        if (isset($_POST['approve'])) { // Утверждение комментария.
            Comments::approveComment($_POST['approve']);
        }

        if (isset($_POST['deny'])) { // Отклонение (удаление) комментария.
            Comments::denyComment($_POST['deny']);
        }

        // Статьи для вывода на страницу
        $article = Articles::getArticleById($id);
        $title = $article->title;

        $comments = Comments::getCommentsByArticleId($id); // Комментарии к статье

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else {
            throw new ApplicationException("$templateFile - шаблон не найден", 444); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}
