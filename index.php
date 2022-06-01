<?php

use App\Application;
use App\Controllers\SiteController;
use App\Controllers\UserController;
use App\Controllers\ArticleController;
use App\Controllers\AdminPageController;
use App\Controllers\PostController;
use App\Controllers\Admin\ArticleController as AdminArticleController;
use App\Controllers\Admin\UserController as AdminUserController;
use App\Router;
use App\Model\Methods;
use App\Components\Menu;

session_start();
error_reporting(E_ALL);
ini_set('display_errors', true);

require_once __DIR__ . '/bootstrap.php';

$router = new Router(); // Объект Маршрутизатора
$application = new Application($router); // Для запуска Eloquent

// Требуется запустить Eloquent. Как вариант - загружать методы из конфиг-файла
foreach (Methods::all() as $method) {  // Метод модели all получит все записи из связанной с моделью таблицы БД
    $router->get($method->uri,      [SiteController::class, 'method']);
    $router->get($method->uri . '/page-*',      [SiteController::class, 'method']);
}

// --- Страницы сайта -----
$router->get('',      [SiteController::class, 'index']); // Маршрут для корня сайта (/) - метод index в App\Controllers\SiteController
$router->post('',      [SiteController::class, 'index']); // Для подписки на рассылку

$router->get('page-*', [SiteController::class, 'index']); // Маршрут для page-1 - пагинация - метод index в App\Controllers\SiteController
$router->post('page-*', [SiteController::class, 'index']); // Для подписки на рассылку

$router->get('about', [SiteController::class, 'about']); // Маршрут для about
$router->get('contacts', [SiteController::class, 'contacts']); // Используем метод contacts класса ArticleController
$router->get('rules', [SiteController::class, 'rules']); // Правила сайта

// --- Статьи -----
$router->get('article/*', [ArticleController::class, 'article']); // Маршрут для выбранной статьи
$router->post('article/*', [ArticleController::class, 'article']); // Маршрут для выбранной статьи

// --- Пользовтель -----
$router->get('subscription', [UserController::class, 'subscription']); // Подписка на рассылку
$router->post('subscription', [UserController::class, 'subscription']); // Подписка на рассылку

$router->get('unsubscribe', [UserController::class, 'unsubscribe']); // Отписка от рассылки
$router->post('unsubscribe', [UserController::class, 'unsubscribe']); // Отписка от рассылки

$router->get('login', [UserController::class, 'login']); // Используем метод login/get для вывода страницы авторизации
$router->post('login', [UserController::class, 'login']); // Используем метод login/post для обработки авторизации

$router->get('lk', [UserController::class, 'lk']); // Используем метод lk для входа в личный кабинет по url = lk
$router->post('lk', [UserController::class, 'lk']); // Используем метод lk для входа в личный кабинет по url = lk

$router->get('registration', [UserController::class, 'registration']); // Используем метод registration класса ArticleController для регистрации
$router->post('registration', [UserController::class, 'registration']); // Используем метод registration класса ArticleController для регистрации

$router->get('exit', [UserController::class, 'exit']); // Используем метод exit класса ArticleController для выхода
$router->post('exit', [UserController::class, 'exit']); // Используем метод exit класса ArticleController для выхода

$router->get('password', [UserController::class, 'password']); // Используем метод password класса ArticleController для выхода
$router->post('password', [UserController::class, 'password']); // Используем метод password класса ArticleController для выхода

// --- Подписка - лог рассылки -----
$router->get('post', [PostController::class, 'post']); // PostController::post - обработка запроса
$router->get('post?*=*', [PostController::class, 'post']);  // 1-я страница - учесть GET-запрос в обработке url
$router->get('post/page-*', [PostController::class, 'post']); // page-* - страница пагинации
$router->get('post/page-*?*=*', [PostController::class, 'post']); // Учесть GET-запрос в обработке url на page-* - странице пагинации

// --- Admin ------
$router->get('admin', [AdminUserController::class, 'admin']); // Маршрут для перехода в админку
$router->get('article-delete/*', [AdminArticleController::class, 'articleDelete']); // Вывод страницы-сообщения об удалении статьи.

foreach (Menu::getAdminMenu() as $key => $value) { // Загрузка маршрутов для админки
    $router->get($key, ["App\Controllers\Admin\\" . $value['class'], $value['method']]); // 1-я страница
    $router->get($key . '?*=*', ["App\Controllers\Admin\\" . $value['class'], $value['method']]);  // 1-я страница - учесть GET-запрос в обработке url
    $router->get($key . '/page-*', ["App\Controllers\Admin\\" . $value['class'], $value['method']]); // page-* - страница пагинации
    $router->get($key . '/page-*?*=*', ["App\Controllers\Admin\\" . $value['class'], $value['method']]); // Учесть GET-запрос в обработке url на page-* - странице пагинации
    $router->post($key, ["App\Controllers\Admin\\" . $value['class'], $value['method']]); // 1-я страница
    $router->post($key . '?*=*', ["App\Controllers\Admin\\" . $value['class'], $value['method']]);  // 1-я страница - учесть GET-запрос в обработке url
    $router->post($key . '/page-*', ["App\Controllers\Admin\\" . $value['class'], $value['method']]); // page-* - страница пагинации
    $router->post($key . '/page-*?*=*', ["App\Controllers\Admin\\" . $value['class'], $value['method']]); // Учесть GET-запрос в обработке url на page-* - странице пагинации
}

$router->get('admin-cms/*', [AdminArticleController::class, 'adminCMS']); // Для редактирования статьи
$router->post('admin-cms/*', [AdminArticleController::class, 'adminCMS']);

// --- @TEST ----------
$router->get('posts/*', [ArticleController::class, 'test']); // @TEST
$router->get('test_index', [ArticleController::class, 'index']); // @TEST
$router->get('test/*/test2/*', [ArticleController::class, 'test']); // @TEST
$router->get('test/*/*/*', [ArticleController::class, 'test']); // @TEST: Строка /test/qwerty/asdfg/115555 будет обработана методом ArticleController::test

// Создание объекта приложения
$application = new Application($router); // Передаем объект Маршрутизатора с маршрутами в объект Приложения
// Запуск приложения
$application->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']); // Запускаем Приложение с парметрами: URL-адрес текущей страницы и HTTP-метод запроса.
