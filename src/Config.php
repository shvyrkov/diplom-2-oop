<?php

namespace App;

/**
 * Config - класс, содержащий конфигурации приложения. Реализует шаблон Singleton.
 * @package App
 */
final class Config
{
    /** 
     * @var Singleton 
     */
    private static $instance;

    /**
     * @var array - многомерный ассоциативный массив с конфигурацией приложения.
     */
    private $configurations = [];

    private function __construct()
    {
        static::load(); // Загрузка конфигурации из файла.
    }

    public static function getInstance(): Config
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /** 
     * Метод возвращает значение запрошенной конфигурации, внутри него примените созданную ранее вспомогательную функцию array_get().
     * @param string $config - строка конфигурации типа 'db.mysql.host'
     * @param mixed $default - сообщение, выдываемое, если искомая конфигурация не найдена.
     *
     * @return string - значение запрошенной конфигурации
     */
    public function get(string $config, $default = null)
    {
        return array_get($this->configurations, $config, $default);
    }

    /** 
     * Метод загружает все конфигурационные файлы проекта из директории config, расположенной в корне проекта, и сохранять массивы из них в свойстве класса $configurations. Название файла является первым ключом в свойстве конфигураций. 
     * Метод вызывается в конструкторе этого класса.
     */
    private function load()
    {
        if (!is_dir(CONFIG_DIR)) { // Проверка наличия директории с конфигурацией

            return;
        }

        if ($dir = opendir(CONFIG_DIR)) { // Открываем директорию
            while (($file = readdir($dir)) !== false) { // Читаем содержимое директории, пока не оно кончится
                if ($file !== '.' && $file !== '..') { // отбросить элементы с именами . и ..
                    if (file_exists(CONFIG_DIR . $file)) {
                        $fileName = explode('.', $file); // Убираем расширение
                        $this->configurations[$fileName[0]] = require CONFIG_DIR . $file; // Добавляем конфигурацию в св-во, где название файла является первым ключом
                    }
                }
            }
            closedir($dir);
        }
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}
