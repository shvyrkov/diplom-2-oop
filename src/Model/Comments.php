<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Класс для работы со статьями
 */
class Comments extends Model
{
    /**
     * Первичный ключ таблицы articles.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * Получение комментариев к статье по её id
     * 
     * @param string $id статьи
     * 
     * @return array $comments - массив с комментариями.
     */
    public static function getCommentsByArticleId($id = 1)
    {
        $comments = Comments::where('article_id', '=', $id)
            ->orderBy('date', 'desc')
            ->get();

        return $comments;
    }

    /**
     * Добавление комментария пользователя к статье
     * 
     * @param string $text <p>Текст комментария</p>
     * @param string $articleId <p>id-статьи</p>
     * @param string $userId <p>id-пользователя</p>
     * @param string $role <p>Роль пользователя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function addComment($text, $articleId, $userId, $role)
    {
        $approve = 0;

        if (in_array($role, [ADMIN, CONTENT_MANAGER])) {
            $approve = 1;
        }

        Comments::insert(
            ['text' => $text, 'article_id' => $articleId, 'user_id' => $userId, 'approve' => $approve]
        );

        return true;
    }

    /**
     * Утверждение комментария
     * 
     * @param string $commentId <p>id-комментария</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function approveComment($commentId)
    {
        Comments::upsert(
            ['id' => $commentId, 'approve' => 1],
            ['id'],
            ['approve']
        );

        return true;
    }

    /**
     * Отклонение комментария
     * 
     * @param string $commentId <p>id-комментария</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function denyComment($commentId)
    {
        Comments::upsert(
            ['id' => $commentId, 'deny' => 1],
            ['id'],
            ['deny']
        );

        return true;
    }

    /**
     * Изменение комментария
     * 
     * @param string $id <p>id-комментария</p>
     * @param string $approve <p>комментарий одобрен</p>
     * @param string $deny <p>комментарий отклонен</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changeComment($id, $approve, $deny)
    {
        Comments::upsert(
            ['id' => $id, 'approve' => $approve, 'deny' => $deny],
            ['id'],
            ['approve', 'deny']
        );

        return true;
    }

    /**
     * Получение комментариев из БД
     * 
     * @param int $limit [optional] Количество комментариев на странице
     * @param int $page [optional] Номер страницы
     * 
     * @return array $comments - массив со комментариями.
     */
    public static function getComments($limit = 20, $page = 1)
    // public static function getComments($limit = 4, $page = 1)
    {
        $comments = []; // массив со статьями
        $offset = ($page - 1) * $limit;

        $comments = Comments::where('id', '>', 0)
            ->orderBy('date', 'desc') // в порядке убывания по дате публикации
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $comments;
    }
}
