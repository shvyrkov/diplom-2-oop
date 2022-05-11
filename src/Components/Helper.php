<?php

namespace App\Components;

/**
 * Класс содержит вспомогательные методы для валидации данных.
 */
class Helper
{

    /**
     * Проверяет длину строки: не меньше, чем $min и не больше, чем $max
     * 
     * @param string $string <p>Строка</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkLength($string, $min, $max)
    {
        if (iconv_strlen($string) >= $min && iconv_strlen($string) <= $max) {

            return true;
        }

        return false;
    }

}
