<?php

namespace App;

use App\Exceptions\NotFoundException;

/**
 * Router - класс для управления маршрутизацией URL-запросов. 
 * Его задача — определить, код какого обработчика нужно сейчас выполнить, на основе URL-адреса и HTTP-метода запроса. 
 * Он же позволяет регистрировать внутри себя маршруты (URL + его обработчик или контроллер).
 * @package App
 */
class Router
{
    /** @var array|Route[]  — массив допустимых маршрутов - объектов Route.*/
    private $routes = [];

    /**
     * Метод get вспомогательный метод, он регистрирует новый маршрут для HTTP-метода запроса GET, используя внутренний метод addRoute.
     *
     * @param string $path - URL-адрес маршрута
     * @param array $callback - массив из двух значений, имя класса контроллера c namespace и имя метода, которые нужно выполнить в качестве обработчика этого маршрута.
     */
    public function get(string $path, array $callback)
    {
        $this->addRoute('get', $path, $callback); // Регистрируем новый маршрут для HTTP-метода запроса GET
    }

    /**
     * Метод post вспомогательный метод, он регистрирует новый маршрут для HTTP-метода запроса POST, используя внутренний метод addRoute.
     *
     * @param string $path - URL-адрес маршрута
     * @param array $callback - массив из двух значений, имя класса контроллера c namespace и имя метода, которые нужно выполнить в качестве обработчика этого маршрута.
     */
    public function post(string $path, array $callback)
    {
        $this->addRoute('post', $path, $callback); // Регистрируем новый маршрут для HTTP-метода запроса POST
    }

    /**
     * Метод addRoute регистрирует маршрут внутри маршрутизатора. $method — это HTTP-метод запроса, $path — URL-адрес маршрута, $callback — массив из двух значений, имя класса контроллера и имя метода, которые нужно выполнить в качестве обработчика этого маршрута.
     *
     * @param string $method - HTTP-метод запроса
     * @param string $path - URL-адрес маршрута
     * @param array $callback - массив из двух значений, имя класса контроллера c namespace и имя метода, которые нужно выполнить в качестве обработчика этого маршрута.
     */
    private function addRoute(string $method, string $path, array $callback)
    {
        $this->routes[] = new Route($method, $path, $callback); // Добавляем новый маршрут в массив допустимых маршрутов
    }

    /**
     * Метод dispatch ищет подходящий маршрут, выполняет и возвращает его результат работы или возвращает ошибку, если подходящего маршрута не найдено.
     * Если при выполнении метода dispatch не было найдено подходящего маршрута, то метод должен выбросить исключение NotFoundException.
     *
     * @param string $url - URL-адрес текущей страницы
     * @param string $method - HTTP-метод запроса
     *
     * @return mixed - возвращает подходящий маршрут или 'page not found', если подходящего маршрута не найдено.
     */
    public function dispatch(string $url, string $method)
    {
        $uri = trim($url, '/');

        foreach ($this->routes as $route) { // Перебираем допустимые маршруты
            if ($route->match($uri, strtolower($method))) { // Если находим совпадающий,
                return $route->run($uri); // то запускаем совпавший маршрут
            }
        }

        throw new NotFoundException("Страница не найдена", 404);
    }
}
