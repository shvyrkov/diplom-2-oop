<?php

use App\Application;
use App\Controllers\HomeController;
use App\Controllers\StaticPageController;
use App\Controllers\AdminPageController;
use App\Controllers\PostController;
use App\Router;
use App\Model\Methods;
use App\Components\Menu;

session_start();
error_reporting(E_ALL);
ini_set('display_errors',true);

require_once __DIR__ . '/bootstrap.php';

$router = new Router(); // Объект Маршрутизатора
$application = new Application($router); // Для запуска Eloquent

$router->get('',      [HomeController::class, 'index']); // Маршрут для корня сайта (/) - метод index в App\Controllers\HomeController
// $router->get('*',      [HomeController::class, 'index']); // Пагинация: количество элементов на странице задается GET-параметром. 
$router->get('page-*', [HomeController::class, 'index']); // Маршрут для page-1 - пагинация - метод index в App\Controllers\HomeController

// Требуется запустить Eloquent. Как вариант - загружать методы из конфиг-файла
foreach (Methods::all() as $method) {  // Метод модели all получит все записи из связанной с моделью таблицы БД
    $router->get($method->uri,      [HomeController::class, 'method']); 
}

$router->get('article/*', [StaticPageController::class, 'article']); // Маршрут для выбранной статьи
$router->post('article/*', [StaticPageController::class, 'article']); // Маршрут для выбранной статьи

$router->get('login', [StaticPageController::class, 'login']); // Используем метод login/get для вывода страницы авторизации
$router->post('login', [StaticPageController::class, 'login']); // Используем метод login/post для обработки авторизации

$router->get('lk', [StaticPageController::class, 'lk']); // Используем метод lk для входа в личный кабинет по url = lk
$router->post('lk', [StaticPageController::class, 'lk']); // Используем метод lk для входа в личный кабинет по url = lk

$router->get('rules', [StaticPageController::class, 'rules']); // Правила сайта

$router->get('registration', [StaticPageController::class, 'registration']); // Используем метод registration класса StaticPageController для регистрации
$router->post('registration', [StaticPageController::class, 'registration']); // Используем метод registration класса StaticPageController для регистрации

$router->get('exit', [StaticPageController::class, 'exit']); // Используем метод exit класса StaticPageController для выхода
$router->post('exit', [StaticPageController::class, 'exit']); // Используем метод exit класса StaticPageController для выхода

$router->get('password', [StaticPageController::class, 'password']); // Используем метод password класса StaticPageController для выхода
$router->post('password', [StaticPageController::class, 'password']); // Используем метод password класса StaticPageController для выхода

$router->get('about', [StaticPageController::class, 'about']); // Маршрут для about
$router->get('contacts', [StaticPageController::class, 'contacts']); // Используем метод contacts класса StaticPageController
$router->get('post', [PostController::class, 'post']); // PostController::post - обработка запроса

$router->get('admin', [AdminPageController::class, 'admin']); // Маршрут для перехода в админку
$router->get('article-delete/*', [AdminPageController::class, 'articleDelete']); // Вывод страницы-сообщения об удалении статьи.

foreach (Menu::getAdminMenu() as $key => $value) { // Загрузка маршрутов для админки
    $router->get($key, [AdminPageController::class, $value['method']]); 
    $router->get($key . '/page-*', [AdminPageController::class, $value['method']]); // Для пагинации(?)
    $router->post($key, [AdminPageController::class, $value['method']]); 
    $router->post($key . '/page-*', [AdminPageController::class, $value['method']]); // Для пагинации(?)
}
//--------------
// foreach (Articles::all() as $article) {
    $router->get('admin-cms/*', [AdminPageController::class, 'adminCMS']); // Для редактирования статьи
    $router->post('admin-cms/*', [AdminPageController::class, 'adminCMS']); 
// }
//----------
    
$router->get('posts/*', [StaticPageController::class, 'test']);
$router->get('test_index', [StaticPageController::class, 'index']); // 
$router->get('test/*/test2/*', [StaticPageController::class, 'test']);
$router->get('test/*/*/*', [StaticPageController::class, 'test']); // Строка /test/qwerty/asdfg/115555 будет обработана методом StaticPageController::test

$application = new Application($router); // Передаем объект Маршрутизатора с маршрутами в объект Приложения

$application->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']); // Запускаем Приложение с парметрами: URL-адрес текущей страницы и HTTP-метод запроса.
