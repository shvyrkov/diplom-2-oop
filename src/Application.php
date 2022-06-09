<?php

namespace App;

use App\View\Renderable;
use App\Exceptions\ApplicationException;
use App\Exceptions\HttpException;
use App\View\View;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Application - основной класс приложения.
 */
class Application
{
    /**
     * @var Router $router — объект класса маршрутизатора.
     */
    private  $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->initialize();
    }

    /**
     * Метод должен проверить тип исключения. 
     * Если исключение реализует интерфейс Renderable, то должен быть вызван метод render() этого исключения. 
     * Если это HttpException, то этот метод самостоятельно должен установить с помощью функции http_response_code() HTTP-статус ответа страницы, код которого является кодом исключения (или 500, если у исключения нулевой код), а также он должен вывести текст ошибки, подключив шаблон ошибки errors/error.php с помощью класса View.
     *
     * @param ApplicationException $e - исключение
     */
    private function renderException(ApplicationException $e)
    {
        if ($e instanceof Renderable) {
            $e->render();
            echo $e->getTraceAsString();
            echo '<br>File: ' . $e->getFile();
            echo '<br>Line: ' . $e->getLine();
        } elseif ($e instanceof HttpException) {
            if ($e->getCode()) {
                http_response_code($e->getCode()); // установить с помощью функции http_response_code() HTTP-статус ответа страницы, код которого является кодом исключения
            } else {
                http_response_code(500); // или 500, если у исключения нулевой код
            }
            // вывести текст ошибки, подключив шаблон ошибки errors/error.php с помощью класса View.
            $view = new View('errors/errors', ['title' => $e->getMessage(), 'linkText' => 'На главную']);

            try {
                $view->render();
            } catch (ApplicationException $e) {
                echo $e->getMessage();
            }
        } else { // ApplicationException - not renderable
            try {
                $view = new View(
                    'errors/errors',
                    [
                        'title' => 'Ошибка: ' . $e->getCode() . '.<br>',
                        'e' => 'ApplicationException: ' . $e->getMessage(),
                        // 'trace' => $e->getTrace(), 
                        'traceAsString' => $e->getTraceAsString(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'linkText' => 'На главную'
                    ]
                );
                $view->render();
            } catch (ApplicationException $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     * Метод run выводит результат работы метода dispatch() маршрутизатора, передавая ему в качестве параметра URL-адрес текущей страницы и HTTP-метод запроса.
     *
     * @param string $url - URL-адрес текущей страницы
     * @param string $method - HTTP-метод запроса
     */
    public function run(string $url, string $method)
    {
        try {
            $view = $this->router->dispatch($url, $method); // Здесь может быть исключение NotFoundException

            if ($view instanceof Renderable) { // Если вызов метода dispatch() класса Router вернёт объект, реализующий интерфейс Renderable, то нужно вызывать метод render() у этого объекта.
                $view->render(); // Вывод заданного шаблона
            } else { // иначе - вывод результата с помощью оператора echo 
                echo $view;
            }
        } catch (ApplicationException $e) { // перехват исключения ApplicationException и его потомков (NotFoundException и HttpException)
            // echo "page not found";
            $this->renderException($e);
        }
    }

    /**
     * Метод конфигурирует подключение к базе данных: создайте новый экземпляр класса, указав корректные настройки подключения к базе данных, и вызовите методы setAsGlobal() и bootEloquent(), точно так же, как это делается в описании на Github. Создавать и регистрировать EventManager не нужно, если вы не будете загружать конфигурационные файлы проекта.

     */
    private function initialize()
    {
        $capsule = new Capsule;
        $config = Config::getInstance(); // Создаем конфигурацию сайта

        $capsule->addConnection([ // Побключаемся к БД
            'driver' => $config->get('db.driver'),
            'host' => $config->get('db.host'),
            'database' => $config->get('db.database'),
            'username' => $config->get('db.username'),
            'password' => $config->get('db.password'),
            'charset' => $config->get('db.charset'),
            'collation' => $config->get('db.collation'),
            'prefix' => $config->get('db.prefix'),
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent(); // Запуск ORM Eloquent
    }
}
