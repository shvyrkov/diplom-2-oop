<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class ArticleMethods extends Model
{
  /**
   * Первичный ключ таблицы article_methods.
   *
   * @var string
   */
  protected $primaryKey = 'id';

  public $timestamps = false;

  /**
   * Получение методов для статьи из БД по id
   * 
   * @param string $id статьи
   * 
   * @return array $articleMethods - методы для статьи.
   */
  public static function getMethodsByArticleId($id)
  {
    $articleMethods = [];
    $methods = ArticleMethods::where('id_article', $id)->get(); // Все связи статьи и методов.

    foreach ($methods as $method) {
      $articleMethods[] = $method->id_method;
    }

    return $articleMethods;
  }
}
