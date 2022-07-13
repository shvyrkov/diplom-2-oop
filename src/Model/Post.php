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

    /**
     * Рассылка по подписке
     *
     * @param Articles $article
     */
    public static function mailing(Articles $article)
    {
        $users = Users::getSubscribedUsers(); // Пользователи, подписанные на рассылку

        $subject = 'На сайте добавлена новая статья: "' . $article->title . '".'; // Заголовок письма: На сайте добавлена новая запись: “#Название новой статьи#”
        $message = 'Новая статья: ' // Содержимое письма
            . $article->title
            . ', <br>Краткое описание статьи: '
            . $article->description; // Краткое описание статьи

        $link = DIRECTORY_SEPARATOR . $_SERVER["HTTP_HOST"] . DIRECTORY_SEPARATOR . ARTICLE . DIRECTORY_SEPARATOR . $article->id; // Ссылка на страницу новой статьи
        $unsubscribe = UNSUBSCRIBE; // Ссылка на страницу отписки

        foreach ($users as $user) { // Все, кто подписан - TODO: сделать метод на запрос

            Post::insertGetId(
                [
                    'email' => $user->email,
                    'subject' => $subject,
                    'message' => $message,
                    'link' => $link,
                    'unsubscribe' => $unsubscribe
                ]
            );
        }
    }
}
