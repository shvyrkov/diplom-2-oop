<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * @package App\Model
 */
class Post extends Model
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
     * Рассылка подписчикам при создании новой статьи
     * 
     * @param string $email - подписчика
     * @param string $subject - Название новой статьи
     * @param string $message - Краткое описание статьи
     * @param string $link - ссылка на страницу со статьей
     * @param string $unsubscribe  - ссылка на страницу со отпиской от рассылки
     * 
     * @return bool - результат рассылки
     */
    public static function mailing($email, $subject, $message, $link, $unsubscribe)
    {
        // Запись в БД
        $id = Post::insertGetId(
            ['email' => $email, 'subject' => $subject, 'message' => $message, 'link' => $link, 'unsubscribe' => $unsubscribe]
        );

        return $id ? true : false;
    }

    /**
     * Получение писем рассылки из БД
     * 
     * @param int $limit [optional] Количество на странице
     * @param int $page [optional] Номер страницы
     * 
     * @return object $mails - объект с письмами рассылки.
     */
    public static function getMails($limit = 20, $page = 1)
    {
        $offset = ($page - 1) * $limit;

        $mails = Post::where('id', '>', 0)
            ->orderBy('date', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $mails;
    }
}
