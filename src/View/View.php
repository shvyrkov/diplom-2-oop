<?php

namespace App\View;

use App\Exceptions\ApplicationException;
use App\Components\Menu;
use App\Model\Users;

/**
* Класс View — шаблонизатор приложения, реализует интерфейс Renderable. Используется для подключения view страницы.
*/
class View implements Renderable
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
    protected function getURI()
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
        $menu = Menu::getUserMenu();

        $templateFile = $this->getIncludeTemplate($this->view); // Полное имя файла

        if (isset($_POST['subscription'])) {
            $result = false;
            $errors = false;
            $user = false;
            $id = $_SESSION['user']['id'] ?? '';

            if ($id) { // Если пользователь авторизован, то подписываем его на рассылку
                $result = Users::changeSubscription($id, 1);
            } else { // Если пользователь НЕавторизован, то 
                $email = $_POST['email'] ?? '';
                // Валидация e-mail
                if (!$email) {
                    $errors[] = 'Не все поля заполнены';
                }

                if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
                    $errors['checkEmail'] = ' - неправильный email';
                }

                if (Users::checkEmailExists($email) ) { //  Есть пользователь
                    $user = Users::getUserByEmail($email);

                    if ($user->name) { // уже авторизованный 
                        $errors['checkEmailExists'] = ' - такой email уже используется, авторизуйтесь пожалуйста.';
                    } elseif ($user->subscription) { // НЕавторизованный, но подписанный пользователь
                        $errors['checkEmailExists'] = ' - Вы уже подписаны на рассылку.';
                    }
                }

                if ($errors === false) { // Если ошибок нет, то регистрируем и подписываем на рассылку пользователя.
                    if (!$user) {
                        $user = new Users(); // Если пользователя нет, то регистрируем его 
                    }

                    $user->email = $email;
                    $user->role = NO_USER; // как NO_USER
                    $user->subscription = 1; // и подписываем его на рассылку
                    $user->save();
                    $id = $user->id;

                    $user = Users::getUserById($id); // Запрашиваем данные подписанного пользователя из БД

                    if ($user->subscription) { // Если у него есть подписка, то всё Ок.
                        // $result = true;
                    }
                }
            }

            if ($result) { // Только для авторизоанного пользователя запрашиваем новые данные -А НАДО?------
                $user = Users::getUserById($id);
            }

            if ($user === false) {
                // Если данные не получены - показываем ошибку
                $errors[] = 'Ошибка получения данных.';
            } else {
                // Если данные правильные, запоминаем пользователя в сессии
                Users::auth($user);

                // Перегружаем сайт с новыми данными
                header('Location: /subscription');
            }
        }

        if (isset($_POST['exit'])) {
            Users::exit();
        }

        if (file_exists($templateFile)) {
            include $templateFile; // Вывод представления
        } else { // Если файл не найден
            throw new ApplicationException("$templateFile - шаблон не найден", 442); // Если запрашиваемого файла с шаблоном не найдено, то метод должен выбросить исключение ApplicationException, с таким текстом ошибки: "<имя файла шаблона> шаблон не найден". 
        }
    }
}
