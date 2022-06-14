<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Methods
 * @package App\Model
 */
class Methods extends Model
{
    /**
     * Первичный ключ таблицы method_types.
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
     * Получение метода из БД по uri
     * 
     * @param string $uri 
     * 
     * @return object $method - данные метода.
     */
    public static function getMethodByURI($uri = 1)
    {
        $method = Methods::where('uri', $uri)
            ->first();

        return $method;
    }
}
