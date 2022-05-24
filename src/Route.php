<?php
namespace App;

use Closure; // Класс, используемый для создания анонимных функций.

/**
 * Route - Этот класс представляет собой экземпляр маршрута. 
 * Его задача — хранить в себе параметры маршрута и уметь запускать код-обработчик выполнения текущего маршрута (выполнять контроллер, указанный в callback-функции).
 */
class Route
{
    /** @var string $method — HTTP-метод запроса.*/
    private  $method;
    /** @var string $path — URL-адрес маршрута.*/
    // private  $path;
    public  $path;
    /** @var Closure $callback — callback-функция?*/
    private  $callback;

    public function __construct(string $method, string $path, array $callback)
    {
        $this->method   = $method;
        $this->path     = $path;
        $this->callback = $this->prepareCallback($callback);
    }

    /**
    * Метод prepareCallback - это внутренний метод маршрутизатора. Он преобразует параметр $callback в выполняемую функцию, чтобы потом использовать её для выполнения маршрута.
    *
    * @param array $callback - [ClassController_name, method_name] - ["App\Controllers\StaticPageController", "about"]
    *
    * @return Closure - ClassController $object->method(...$params)
    */
    private function prepareCallback(array $callback): Closure
    {
        return function (...$params) use ($callback) {
            list($class, $method) = $callback; // Берем первые 2 значения 
            return (new $class)->{$method}(...$params); // Получаем объект класса переданного контроллера и вызываем его метод с полученными из URI параметрами
        };
    }

    /**
    * Метод getPath - это метод-геттер. Он просто возвращает текущее значение свойства $path.
    *
    * @return string - текущее значение свойства $path
    */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
    * Метод match проверяет, подходит ли текущий маршрут текущему запросу.
    * вместо прямого сравнения URL из параметра и значения свойства path примените регулярное выражение, которое позволит вам обрабатывать виды маршрутов указанных выше: preg_match('/^' . str_replace(['*', '/'], ['\w+', '\/'], $this->getPath()) . '$/', $uri);
    *
    * @param string $uri - URL-адрес текущей страницы
    * @param string $method - HTTP-метод запроса
    *
    * @return bool 
    */
    public function match(string $uri, string $method): bool
    {
// Учесть GET-запрос в обработке url:
// echo "<pre>";
// echo "<br>uri:"; // Строка запроса - 
// var_dump($uri); // string(21) "admin-articles/page-4"
// echo "<br>getPath:"; // Маршрут из списка -
// var_dump($this->getPath()); // string(21) "admin-articles/page-*"
// echo "<br>str_replace:";
// var_dump('/^' . str_replace(['*', '/', '?', '='], ['\w+', '\/', '\?', '\='], $this->getPath()) . '$/'); // "/^admin-articles\/page-\w+$/"
// echo "<br>";
// echo "</pre>";

        return ((preg_match('/^' . str_replace(['*', '/', '?', '='], ['\w+', '\/', '\?', '\='], $this->getPath()) . '$/', $uri)) && ($this->method == $method)); // Учитывает GET-запрос в обработке url
    }

    /**
    * Метод run запускает обработчик маршрута и возвращает результат его работы.
    * Метод принимает параметрЫ текущего uri, которые передаётся ему из класса Router. Внутри этого метода получаем список параметров из URL и в виде массива передаем вторым параметром в вызов функции call_user_func_array(), чтобы эти параметры попали в контроллер.
    *
    * @return результат функции или false в случае возникновения ошибки.
    */
    public function run($uri = null)
    {
        $params = explode('/', $uri); // Переводим строку запроса в массив параметров
        array_shift($params); // Убираем 1-й эл-т (это метод в контроллере), остаются только параметры

        // Возвращает результат функции или false в случае возникновения ошибки.
        return call_user_func_array($this->callback, $params); // Вызывает callback-функцию с массивом параметров
    }
}
