<?php
namespace App\Exceptions;

use App\View\Renderable;
use App\View\View;

/**
* NotFoundException extends HttpException implements Renderable — класс исключения для вывода ошибки 404.
*/
class NotFoundException extends HttpException implements Renderable
{
    /**
    * Метод с помощью функции header() должен установить HTTP-статус ответа в значение 404 и заголовок ответа: HTTP/1.0 404 Not Found. С помощью класса View он должен вывести шаблон страницы errors/errors.php, установив в качестве текста: "Ошибка 404. Страница не найдена".
    */
    public function render() 
    {
        header("Status: 404");
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found"); // чтобы переопределить сообщения о состоянии http для клиентов, которые все еще используют HTTP/1.0

        $view = new View('errors/errors', 
            ['title' => 'Ошибка 404. Страница не найдена', 'linkText' => 'На главную', 'e' => '']);

        try {
            $view->render();
        } catch (ApplicationException $e) {
            echo $e->getMessage();
            $view->data = ['title' => 'Ошибка 456. Страница не найдена', 'linkText' => 'На главную', 'e' => $e];
            $_POST['e'] = $e;
        }
    }
}
