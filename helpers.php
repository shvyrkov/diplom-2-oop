<?php

function dd(...$params)
{
    echo '<pre>';
    var_dump($params);
    echo '</pre>';
    die;
}

function dump(...$params)
{
    echo '<pre>';
    var_dump($params);
    echo '</pre>';
}

/** Функция получает из многомерного массива элемент по ключу в виде строки, где каждый уровень вложенности отделён точкой. Если такой элемент не будет найден, то функция вернёт значение по умолчанию. Например, ключ 'db.mysql.host' должен достать из массива значение 'localhost': 
[
    'db' => [
        'mysql' => [
            'host' => 'localhost'
        ]
    ]
]
*/
function array_get(array $array, string $key, $default = null)
{
    $keys = explode('.', $key);
    static $result;

    if (array_key_exists($keys[0], $array)) {
        if (is_array($array[$keys[0]])) {
            $key = array_shift($keys);
            $keys = implode('.', $keys);
            array_get($array[$key], $keys, $default); // Рекурсия: [db, mysql, host] -> [mysql, host] -> [host]
        } else {
            $result = $array[$key];
        }
    } else {
        $result = $default;
    }

    if ($result) {

        return $result;
    }
}
