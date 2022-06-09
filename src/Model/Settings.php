<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Таблица settings
 */
class Settings extends Model
{
    /**
     * Первичный ключ таблицы Users.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * Изменение настройки
     * 
     * @param string $id <p>id-настройки в БД</p>
     * @param string $value <p>Значение настройки</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changeSetting($id, $value)
    {
        Settings::where('id', $id)
            ->update(['value' => $value]);

        return true;
    }
}
