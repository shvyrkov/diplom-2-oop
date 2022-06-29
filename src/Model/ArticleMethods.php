<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleMethods
 * @package App\Model
 */
class ArticleMethods extends Model
{
  /**
   * Первичный ключ таблицы article_methods.
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
   * Получение методов для статьи из БД по id
   * 
   * @param int $id статьи
   * 
   * @return array $articleMethods - методы для статьи.
   */
  public static function getMethodsByArticleId(int $id): array
  {
    $articleMethods = [];

    $methods = ArticleMethods::where('id_article', $id)->get(); // Все связи статьи и методов.

    foreach ($methods as $method) {
      $articleMethods[] = $method->id_method;
    }

    return $articleMethods;
  }
}
