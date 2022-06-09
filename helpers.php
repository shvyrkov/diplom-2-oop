<?php

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
