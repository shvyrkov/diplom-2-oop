<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class class Settings extends Model
 * @package App\Model
 */
class Settings extends Model
{
    /**
     * Первичный ключ таблицы Users.
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
     * Изменение настройки
     * 
     * @param string $id <p>id-настройки в БД</p>
     * @param string $value <p>Значение настройки</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changeSetting($id, $value)
    {
        $result = true;

        $oldValue = Settings::where('id', $id)->first();

        if ($value != $oldValue->value) {
            $result = Settings::where('id', $id)
                ->update(['value' => $value]);
        }

        return $result ? true : false;
    }
}
