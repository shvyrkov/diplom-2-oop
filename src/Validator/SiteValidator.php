<?php

namespace App\Validator;

use App\Model\Settings;

/**
 * Класс SiteValidator - используется для валидации данных изменения настроек сайта
 * @package App\Validator
 */
class SiteValidator
{
    /**
     * Валидация текста комментария
     * 
     * @param int $id - настройки в БД
     * @param string  $value - значение настройки
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function adminSettingsValidate(int $id, string $value)
    {
        if (!$value) {
            $errors[] = 'Некорректные данные. Обратитесь к администртору!';
        } elseif (!Settings::where('id', $id)->first()) {
            $errors[] = 'Неверные данные настройки. Обратитесь к администртору!';
        } elseif (!is_numeric($value)) {
            $errors['value'] = 'Неверные данные значения настройки. Обратитесь к администртору!';
        }

        return $errors ?? null;
    }
}
