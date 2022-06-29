<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comments для работы с комментариями
 * @package App\Model
 */
class Comments extends Model
{
    /**
     * Первичный ключ таблицы articles.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
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
        $comments = Comments::where('article_id', $id)
            ->orderBy('date', 'desc')
            ->get();

        return $comments;
    }

    /**
     * Добавление комментария пользователя к статье
     * 
     * @param string $text <p>Текст комментария</p>
     * @param string $articleId <p>id-статьи</p>
     * @param Users $user <p>Данные пользователя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function addComment(string $text, string $articleId, Users $user)
    {
        $approve = 0;

        if (in_array($user->role, [ADMIN, CONTENT_MANAGER])) {
            $approve = 1;
        }

        $id = Comments::insertGetId(
            ['text' => $text, 'article_id' => $articleId, 'user_id' => $user->id, 'approve' => $approve]
        );

        return $id ? true : false;
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
        $result = Comments::where('id', $commentId)
            ->update(['approve' => 1]);

        return $result ? true : false;
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
        $result = Comments::where('id', $commentId)
            ->update(['deny' => 1]);

        return $result ? true : false;
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
        $result = Comments::where('id', $id)
            ->update(['approve' => $approve,  'deny' => $deny]);

        return $result ? true : false;
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
    {
        $offset = ($page - 1) * $limit;

        $comments = Comments::where('id', '>', 0)
            ->orderBy('date', 'desc') // в порядке убывания по дате публикации
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $comments;
    }
}
