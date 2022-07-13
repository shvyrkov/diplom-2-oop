<?php

namespace App\View;

use App\Exceptions\ApplicationException;

/**
 * Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
 * @package App\View
 */
class AdminView implements Renderable
{
    /**
     * @var string $view — название шаблона, который нужно подключить. 
     */
    protected $view;

    /**
     * @var array $data — данные для шаблона. 
     */
    protected $data;

    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Метод формирует абсолютный путь к файлу шаблона, указанного в свойстве $view с использованием константы VIEW_DIR. При этом в качестве шаблона можно указать строку personal.messages.show, в этом случае метод должен подключить файл шаблона personal/messages/show.php, который должен располагаться в директории view.
     *
     * @param string $view — название шаблона, котор
     *
     * @return string - абсолютный путь к файлу с шаблоном
     */
    protected function getIncludeTemplate($view)
    {
        return $_SERVER["DOCUMENT_ROOT"] . VIEW_DIR . str_replace('.', DIRECTORY_SEPARATOR, $view) . '.php'; // personal.messages.show -> __DIR__ . VIEW_DIR . 'personal/messages/show.php'
    }

    /**
     * Возвращает строку запроса
     *
     * @return srting 
     */
    public static function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * Метод выводит необходимый шаблон.
     */
    public function render()
    {
        /** метод должен выводить необходимый шаблон. Внутри метода данные свойства $data распаковать в переменные через extract(), а затем подключить страницу шаблона, получив полный путь к ней с помощью другого метода этого класса getIncludeTemplate().
         */
        extract($this->data); // ['title' => 'Index Page'] -> $title = 'Index Page' - создается переменная для исп-я в html

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (file_exists($templateFile)) {
            include $templateFile; 
        } else { 
            throw new ApplicationException("$templateFile - шаблон для страницы в админке не найден", 442); 
        }
    }
}
