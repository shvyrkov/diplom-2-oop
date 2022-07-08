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
        if (mb_strlen($string) >= $min && mb_strlen($string) <= $max) {

            return true;
        }

        return false;
    }

    /**
     * Функция перевода байтов в Mb, kB или b в зависимости от их количества
     *
     * @param int $bytes - количество байт
     * 
     * @return string $bytes - количество байт, переведенное в Mb, kB или b в зависимости от их количества
     */
    public static function formatSize($bytes)
    {

        if ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' Mb';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        } else {
            $bytes = $bytes . ' b';
        }

        return $bytes;
    }

    /**
     * Вывод массива ошибок в верстку
     *
     * @param array $errors
     * HTML с ошибками
     * @return string  $errorString - HTML с ошибкамив в виде списка: <li>error</li>
     */
    public static function getErrors(array $errors = []): string
    {
        $errorString = '';

        foreach ($errors as $error) {
            $errorString .=  $errorString . '<li class="font-error">' . $error . '</li>';
        }

        return $errorString;
    }
}
