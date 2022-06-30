<?php

use App\Application;
use App\Controllers\CommentController;
use App\Controllers\SiteController;
use App\Controllers\UserController;
use App\Controllers\ArticleController;
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

$router = new Router();
$application = new Application($router); // Для запуска Eloquent, чтобы получить доступ к Methods

foreach (Methods::all() as $method) {
    $router->get($method->uri,      [ArticleController::class, 'showArticleByMethod']);
    $router->get($method->uri . '/page-*',      [ArticleController::class, 'showArticleByMethod']);
}
// --- Страницы сайта -----
$router->get('',      [SiteController::class, 'index']); // Маршрут для корня сайта (/) - метод index в App\Controllers\SiteController
$router->post('',      [SiteController::class, 'index']); // @TODO: Для обработки формы (?) - @TODO: м.б.сделать ссылку на стр.подписки? - НЕТ

$router->get('page-*', [SiteController::class, 'index']); // Маршрут для page-1 - пагинация - метод index в App\Controllers\SiteController
$router->post('page-*', [SiteController::class, 'index']); //  @TODO: Для обработки формы (?)

$router->get('about', [SiteController::class, 'about']);
$router->get('contacts', [SiteController::class, 'contacts']);
$router->get('rules', [SiteController::class, 'rules']); // Правила сайта

// --- Статьи -----
$router->get('article/*', [ArticleController::class, 'showArticle']);

// ---Комментарии -----
$router->post('article/*', [CommentController::class, 'addComment']);
$router->post('article/*/*/approve', [CommentController::class, 'approveComment']);
$router->post('article/*/*/deny', [CommentController::class, 'denyComment']);

// --- Пользовтель -----
$router->get('subscription', [UserController::class, 'subscription']); // Подписка на рассылку
$router->post('subscription', [UserController::class, 'subscription']); // Подписка на рассылку

$router->get('unsubscribe', [UserController::class, 'unsubscribe']); // Отписка от рассылки
$router->post('unsubscribe', [UserController::class, 'unsubscribe']); // Отписка от рассылки

$router->get('login', [UserController::class, 'login']);
$router->post('login', [UserController::class, 'login']);

$router->get('lk', [UserController::class, 'lk']);
$router->post('lk', [UserController::class, 'lk']);

$router->get('registration', [UserController::class, 'registration']); 
$router->post('registration', [UserController::class, 'registration']); 

$router->get('exit', [UserController::class, 'exit']);
$router->post('exit', [UserController::class, 'exit']);

$router->get('password', [UserController::class, 'password']);
$router->post('password', [UserController::class, 'password']);

// --- Подписка - лог рассылки -----
$router->get('post', [PostController::class, 'post']); // PostController::post - обработка запроса
$router->get('post?*=*', [PostController::class, 'post']);  // 1-я страница - учесть GET-запрос в обработке url
$router->get('post/page-*', [PostController::class, 'post']); // page-* - страница пагинации
$router->get('post/page-*?*=*', [PostController::class, 'post']); // Учесть GET-запрос в обработке url на page-* - странице пагинации

// --- Admin ------
$router->get('admin', [AdminUserController::class, 'admin']); // Маршрут для перехода в админку
$router->get('article-delete/*', [AdminArticleController::class, 'articleDelete']); // Вывод страницы-сообщения об удалении статьи.

foreach (Menu::getAdminMenu() as $key => $value) { // Загрузка маршрутов для админки
    $router->get($key, [$value['class'], $value['method']]); // 1-я страница
    $router->get($key . '?*=*', [$value['class'], $value['method']]);  // 1-я страница - учесть GET-запрос в обработке url
    $router->get($key . '/page-*', [$value['class'], $value['method']]); // page-* - страница пагинации
    $router->get($key . '/page-*?*=*', [$value['class'], $value['method']]); // Учесть GET-запрос в обработке url на page-* - странице пагинации
    $router->post($key, [$value['class'], $value['method']]); // 1-я страница
    $router->post($key . '?*=*', [$value['class'], $value['method']]);  // 1-я страница - учесть GET-запрос в обработке url
    $router->post($key . '/page-*', [$value['class'], $value['method']]); // page-* - страница пагинации
    $router->post($key . '/page-*?*=*', [$value['class'], $value['method']]); // Учесть GET-запрос в обработке url на page-* - странице пагинации
}

$router->get('admin-cms/*', [AdminArticleController::class, 'adminCMS']); // Для редактирования статьи
$router->post('admin-cms/*', [AdminArticleController::class, 'adminCMS']);

// Создание объекта приложения
$application = new Application($router); // Передаем объект Маршрутизатора с маршрутами в объект Приложения
// Запуск приложения
$application->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']); // Запускаем Приложение с парметрами: URL-адрес текущей страницы и HTTP-метод запроса.
