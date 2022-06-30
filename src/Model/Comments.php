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
     * @param int $id статьи
     * 
     * @return object $comments - объект с комментариями к статье
     */
    public static function getCommentsByArticleId(int $id)
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
     * @param int $articleId <p>id-статьи</p>
     * @param Users $user <p>Данные пользователя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function addComment(string $text, int $articleId, Users $user): bool
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
     * @param int $commentId <p>id-комментария</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function approveComment(int $commentId): bool
    {
        $result = Comments::where('id', $commentId)
            ->update(['approve' => 1]);

        return $result ? true : false;
    }

    /**
     * Отклонение комментария
     * 
     * @param int $commentId <p>id-комментария</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function denyComment(int $commentId): bool
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
