<?php

namespace App\Components;

/**
 * Класс для управления выводом общих меню.
 */
class Menu
{
    /**
    * Метод возвращает типы методов из БД ??? @TODELETE
    *
    * @return array
    */
    public static function getMethodTypes()
    {
        $menuPath = []; // 
// @TODO: получить типы методов из БД через Eloquent

        return $menuPath;
    }

    /**
    * Метод возвращает меню пользователя
    *
    * @return array
    */
    public static function getUserMenu()
    {
        return include(CONFIG_DIR . USER_MENU); // Загрузка из файла массива с меню пользователя
    }

    /**
    * Метод возвращает меню администратора
    *
    * @return array
    */
    public static function getAdminMenu()
    {
        return include(CONFIG_DIR . ADMIN_MENU); // Загрузка из файла массива с меню пользователя
    }

    /**
    * Метод получения активного пункта меню из URL
    *
    * @param string $url - данные пункта меню array['path']
    * 
    * @return bool - true, если $_SERVER["REQUEST_URI"] == $url
    */
    public static function isCurrentUrl(string $url = '/'): bool
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) == $url;
    }

    /**
    * Метод вывода заголовка страницы
    *
    * @param array $menu - массив с данными меню
    * 
    * @return string - заголовок страницы
    */
    public static function showTitle(array $menu) {
        $title = '';
        foreach ($menu as $value) {
            if (static::isCurrentUrl($value['path'])) {
                $title = $value['title'];
            }
        }

        return $title;
    }
}
