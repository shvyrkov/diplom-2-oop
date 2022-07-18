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
    $router->get($method->uri,      [ArticleController::class, 'showArticlesByMethod']);
    $router->get($method->uri . '/page-*',      [ArticleController::class, 'showArticlesByMethod']);
}
// --- Страницы сайта -----
$router->get('',      [SiteController::class, 'index']);
$router->get('page-*', [SiteController::class, 'index']);

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
$router->get('subscription', [UserController::class, 'subscription']);
$router->post('subscription', [UserController::class, 'subscription']);

$router->get('unsubscribe', [UserController::class, 'unsubscribe']);
$router->post('unsubscribe', [UserController::class, 'unsubscribe']);

$router->get('login', [UserController::class, 'login']);
$router->post('login', [UserController::class, 'login']);

$router->get('lk', [UserController::class, 'lk']);
$router->post('lk', [UserController::class, 'lk']);

$router->get('registration', [UserController::class, 'registration']);
$router->post('registration', [UserController::class, 'registration']);

$router->get('exit', [UserController::class, 'exit']);

$router->get('password', [UserController::class, 'password']);
$router->post('password', [UserController::class, 'password']);

// --- Подписка - лог рассылки -----
$router->get('post', [PostController::class, 'mailingLog']); // PostController::mailingLog - вывод лога рассылки
$router->get('post?*=*', [PostController::class, 'mailingLog']);  // 1-я страница - учесть GET-запрос в обработке url
$router->get('post/page-*', [PostController::class, 'mailingLog']); // page-* - страница пагинации
$router->get('post/page-*?*=*', [PostController::class, 'mailingLog']); // Учесть GET-запрос в обработке url на page-* - странице пагинации

// --- Admin ------
$router->get('admin', [AdminUserController::class, 'admin']); // Маршрут для перехода в админку

foreach (Menu::getAdminMenu() as $key => $value) { // Загрузка маршрутов для страниц админки
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

$router->get('new-article', [AdminArticleController::class, 'newArticle']);
$router->get('article-delete/*', [AdminArticleController::class, 'articleDelete']);

// Создание объекта приложения
$application = new Application($router); // Передаем объект Маршрутизатора с маршрутами в объект Приложения
// Запуск приложения
$application->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']); // Запускаем Приложение с парметрами: URL-адрес текущей страницы и HTTP-метод запроса.
