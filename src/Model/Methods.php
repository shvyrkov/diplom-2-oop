<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 
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
     * Получение метода из БД по uri
     * 
     * @param string $uri 
     * 
     * @return object $method - данные метода.
     */
    public static function getMethodByURI($uri = 1)
    {
        $method = Methods::where('uri', '=', $uri)
            ->get();

        return $method;
    }
}
