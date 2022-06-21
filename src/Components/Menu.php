<?php

namespace App\Components;

/**
 * Класс для управления выводом общих меню.
 */
class Menu
{
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
        return include(CONFIG_DIR . ADMIN_MENU); // Загрузка из файла массива с меню админки
    }

    /**
     * Метод получения активного пункта меню из URL
     *
     * @param string $url - данные пункта меню array['path']
     * 
     * @return bool - true, если $url начинает $_SERVER["REQUEST_URI"]
     */
    public static function isCurrentUrl(string $url = '/'): bool
    {
        return preg_match('~^' . $url . '~', $_SERVER["REQUEST_URI"] );
    }

    /**
     * Метод вывода заголовка страницы
     *
     * @param array $menu - массив с данными меню
     * 
     * @return string - заголовок страницы
     */
    public static function showTitle(array $menu)
    {
        $title = '';
        foreach ($menu as $value) {
            if (static::isCurrentUrl($value['path'])) {
                $title = $value['title'];
            }
        }

        return $title;
    }
}
