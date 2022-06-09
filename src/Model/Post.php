<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class Post extends Model
{
    /**
     * Первичный ключ таблицы articles.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
     * @return bool 
     */
    public static function mailing($email, $subject, $message, $link, $unsubscribe)
    {
        // Запись в БД
        $mail = Post::insert(
            ['email' => $email, 'subject' => $subject, 'message' => $message, 'link' => $link, 'unsubscribe' => $unsubscribe]
        );

        if (isset($mail)) {

            return true;
        }

        return false;
    }

    /**
     * Получение писем рассылки из БД
     * 
     * @param int $limit [optional] Количество на странице
     * @param int $page [optional] Номер страницы
     * 
     * @return array $mails - массив с письмами рассылки.
     */
    public static function getMails($limit = 20, $page = 1)
    {
        $mails = [];
        $offset = ($page - 1) * $limit;

        $mails = Post::where('id', '>', 0)
            ->orderBy('date', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $mails;
    }
}
