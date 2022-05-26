<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\DB;

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

    // @TODO: Запись в log-файл
    // @TODO: E-mail рассылка

        if (isset($mail)) {

            return true;
        }

        return false;
    }

}
