<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ArticleMethods;
use App\Model\Methods;
use App\Model\Settings;

/**
 * Класс для работы со статьями
 */
class Articles extends Model
{
    /**
     * Первичный ключ таблицы articles.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
    * Получение типов методов, к которым принадлежит статья (замена JOIN для Eloquent)
    *
    * @var - int - $articleId - id-статьи в БД
    *
    * @return array $methodTypes - массив методов, к которым принадлежит статья.
    */
    public static function getMethods($articleId = 0)
    {
        $methods = []; // методы, к которым принадлежит статья

        foreach (ArticleMethods::all() as $articleMethod) {
            if ($articleId == $articleMethod->id_article) {

                foreach (Methods::all() as $method) {
                    if ($articleMethod->id_method == $method->id) {
                        $methods[] = $method;
                    }
                    
                }
            }
        }

        return $methods;
    }

    /**
    * Получение статей из БД
    * 
    * @param int $limit [optional] Количество статей на странице
    * @param int $page [optional] Номер страницы
    * 
    * @return array $articles - массив со статьями.
    */
    public static function getArticles($limit = 4, $page = 1)
    {
        $articles = []; // массив со статьями
        $offset = ($page - 1) * $limit;

        $articles = Articles::where('id', '>' , 0)
                ->orderBy('date', 'desc') // в порядке убывания по дате публикации
                ->offset($offset)
                ->limit($limit)
                ->get();

        return $articles;
    }

    /**
    * Получение статьи из БД по id
    * 
    * @param string $id 
    * 
    * @return array $article[0] - данные по статье.
    */
    public static function getArticleById($id = 1)
    {
        $article = Articles::where('id', $id)
                ->get();

        return $article[0];
    }

    /**
    * Получение статьи по типу метода
    * 
    * @param string $methodId
    * 
    * @return array $articles - массив со статьями.
    */
    public static function getArticlesByMethod($methodId = 4)
    {
        $articles = []; // статьи, которые принадлежат данному методу

        foreach (ArticleMethods::all() as $articleMethod) {
            if ($methodId == $articleMethod->id_method) {

                foreach (Articles::all() as $article) {
                    if ($articleMethod->id_article == $article->id) {
                        $articles[] = $article;
                    }
                }
            }
        }

        return $articles;
    }

    /**
    * Получение количества статей на странице
    * 
    * @return int $articlesQtyOnPage
    */
    public static function getArticlesQtyOnPage()
    {
        $articlesQtyOnPage = Settings::where('name', '=' , 'article_quantity_on_page')
                ->get('value');

        return $articlesQtyOnPage[0]->value;
    }
}
