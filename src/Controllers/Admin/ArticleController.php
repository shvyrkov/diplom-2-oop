<?php

namespace App\Controllers\Admin;

use \SplFileInfo;
use App\Components\Helper;
use App\Components\Menu;
use App\Components\Pagination;
use App\Components\SimpleImage;
use App\Model\Articles;
use App\Model\ArticleMethods;
use App\Model\Comments;
use App\Model\Methods;
use App\Model\Post;
use App\Model\Users;
use App\View\AdminView;

/**
 * Класс ArticleController - контроллер для работы со статьями в админке
 * @package App\Controllers\Admin
 */
class ArticleController
{
    /**
     * Вывод страницы 'Управление статьями'
     *
     * @return AdminView
     */
    public function adminArticles()
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру, которые вошли в свой аккаунт

            $total = Articles::all()->count(); // Всего товаров в БД
            $uri = AdminView::getURI(); // Получаем строку запроса без корня
            // $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
            $page = ($uri == 'admin-articles') ? 1 : preg_replace('~admin-articles/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел /admin-articles, то - 1
            $selected = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
            $page = $selected['page']; // Номер страницы

            if ($selected['limit'] == 'all' || $selected['limit'] > $total) {
                $limit = $total;
            } else {
                $limit = $selected['limit']; // Количество статей на странице в админке 
            }

            return new AdminView(
                'admin-articles',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'articles' => Articles::getArticles($limit, $page), // Статей для вывода на страницу
                    'pagination' => new Pagination($total, $page, $limit, 'page-'), // Постраничная навигация
                    'total' =>  $total, // Всего товаров в БД
                    'limit' =>  $limit, //  Количество товаров на странице
                    'selected' =>  $selected, // Настройка количества товаров на странице
                ]
            );
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы 'Управление комментариями'
     *
     * @return AdminView
     */
    public function adminComments()
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру
            $errors = false;

            if (isset($_POST['submit'])) { // Измененине комментария
                $id = $_POST['id'];
                $approve = $_POST['approve'] ?? 0;
                $deny = $_POST['deny'] ?? 0;

                // Валидация полей
                if (!(is_numeric($id) && in_array($approve, [0, 1]) && in_array($deny, [0, 1]))) { // Индексы д.б.целыми числами.
                    $errors[] = 'Некорректные данные. Обратитесь к администртору!';
                }

                if ($errors === false) { // Если ошибок нет, то
                    Comments::changeComment($id, $approve, $deny);
                }
            }

            $total = Comments::all()->count(); // Всего товаров в БД
            $uri = AdminView::getURI(); // Получаем строку запроса без корня
            // $page = $uri ? preg_replace(PAGE_PATTERN, '$1', $uri) : 1; // получить номер текущей страницы
            $page = ($uri == 'admin-comments') ? 1 : preg_replace('~admin-comments/page-([0-9]+)~', '$1', $uri); // получить номер текущей страницы: если это первый приход в раздел /admin-articles, то - 1
            $selected = Pagination::goodsQuantity($page); // Настройка количества товаров на странице
            $page = $selected['page']; // Номер страницы

            if ($selected['limit'] == 'all' || $selected['limit'] > $total) {
                $limit = $total;
            } else {
                $limit = $selected['limit']; // Количество статей на странице в админке 
            }

            return new AdminView(
                'admin-comments',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'comments' => Comments::getComments($limit, $page), // Комментарии для вывода на страницу
                    'pagination' => new Pagination($total, $page, $limit, 'page-'), // Постраничная навигация
                    'total' =>  $total, // Всего товаров в БД
                    'limit' =>  $limit, //  Количество товаров на странице
                    'selected' =>  $selected, // Настройка количества товаров на странице
                    'errors' => $errors
                ]
            );
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы 'Управление статичными страницами' - создание/редактирование статьи
     *
     * @return AdminView
     */
    public function adminCMS($id = 0)
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру
            $image = '';
            $thumbnail = '';
            $errors = false;
            $success = false;

            if (isset($_POST['delete'])) { // Удаление статьи
                $success = Articles::where('id', $id)->delete(); // Удаляет всё и из article-methods

                header("Location: /article-delete/$success");
            }

            if (isset($_POST['submit'])) { // Обработка формы добавления/редактирования статьи
                $articleTitle = $_POST['articleTitle'] ?? '';
                $subtitle = $_POST['subtitle'] ?? '';
                $people = $_POST['people'] ?? '';
                $duration = $_POST['duration'] ?? '';
                $description = $_POST['description'] ?? '';
                $author = $_POST['author'] ?? '';
                $link = $_POST['link'] ?? '';
                $articleMethods = $_POST['articleMethods'] ?? [];
                $content = $_POST['content'] ?? '';

                // Валидация данных ввода
                if (!Helper::checkLength($articleTitle, MIN_TITLE_LENGTH, MAX_TITLE_LENGTH)) {
                    $errors['articleTitle'] = 'Название статьи не должно быть меньше ' . MIN_TITLE_LENGTH . ' и не больше ' . MAX_TITLE_LENGTH . ' символов';
                }

                if ($subtitle && !Helper::checkLength($subtitle, 0, MAX_SUBTITLE_LENGTH)) {
                    $errors['subtitle'] = 'Название подзаголовка не должно быть больше ' . MAX_SUBTITLE_LENGTH . ' символов';
                }

                if (!Helper::checkLength($people, MIN_PEOPLE_LENGTH, MAX_PEOPLE_LENGTH)) {
                    $errors['people'] = 'Количество символов в поле не должно быть больше ' . MAX_PEOPLE_LENGTH;
                }

                if (!Helper::checkLength($duration, MIN_PEOPLE_LENGTH, MAX_DURATION_LENGTH)) {
                    $errors['duration'] = 'Количество символов в поле не должно быть больше ' . MAX_DURATION_LENGTH;
                }

                if ($description && !Helper::checkLength($description, MIN_TITLE_LENGTH, MAX_DESCRIPTION_LENGTH)) {
                    $errors['description'] = 'Должно быть не меньше ' . MIN_TITLE_LENGTH . ' и не больше ' . MAX_DESCRIPTION_LENGTH . ' символов';
                }

                if (!Helper::checkLength($author, MIN_TITLE_LENGTH, MAX_AUTHOR_LENGTH)) {
                    $errors['author'] = 'Должно быть не меньше ' . MIN_TITLE_LENGTH . ' и не больше ' . MAX_AUTHOR_LENGTH . ' символов';
                }

                if ($link && !Helper::checkLength($link, 0, MAX_LINK_LENGTH)) {
                    $errors['link'] = 'Количество символов в поле не должно быть больше ' . MAX_LINK_LENGTH;
                }

                if ($content && !Helper::checkLength($content, 0, MAX_CONTENT_LENGTH)) {
                    $errors['content'] = 'Количество символов в поле не должно быть больше ' . MAX_CONTENT_LENGTH;
                }

                // Валидация типов методов
                $methodsObj = Methods::all();
                $methodsArr = [];

                foreach ($methodsObj as $method) {
                    $methodsArr[] = $method->id;
                }

                if (is_array($articleMethods)) {
                    foreach ($articleMethods as $method) {
                        if (!in_array($method, $methodsArr)) {
                            $errors['method'] = 'Ошибка ввода типа метода. Обратитесь к Администратору.';
                        }
                    }
                }

                if ($_FILES['myfile']['name'] != '') { // Проверка на наличие файла для загрузки
                    $types = include(CONFIG_DIR . IMAGE_TYPES); // Допустимые типы файла изображения
                    $fileError = SimpleImage::imageFileValidation($types, FILE_SIZE, $_FILES); // Валидация файла изображения

                    if ($fileError) {
                        $errors['file'] = $fileError; // Если валидация не прошла, то добавляем её ошибки
                    }

                    if ($errors === false) { // Загружаем файл на сервер

                        $myfile = new SplFileInfo($_FILES['myfile']['name']); // Загружаемое имя файла с расширением
                        $image = $myfile->getFilename();
                        $fileMoved = move_uploaded_file($_FILES['myfile']['tmp_name'], IMG_STORAGE . $image); // Загрузка файла на сервер

                        $thumbnail = 'small-' . $image;

                        $thumbnailObj = new SimpleImage();
                        $thumbnailObj->load(IMG_STORAGE . $image);
                        $thumbnailObj->resize(490, 280); // Изменение размера изображения для Главной.
                        $thumbnailObj->save(IMG_STORAGE . $thumbnail);

                        $imageObj = new SimpleImage();
                        $imageObj->load(IMG_STORAGE . $image);
                        $imageObj->resize(1100, 620); // Изменение размера изображения для страницы статьи.
                        $imageObj->save(IMG_STORAGE . $image);

                        if (!$fileMoved) {
                            $errors['file']['LoadServerError'] = 'Файл ' . $image . ' не был загружен на сервер';
                        }
                    }
                }

                if ($errors === false) { // Если ошибок нет, то добавляем данные.
                    $newArticle = false;

                    if ($id) { // Редактирование существующей статьи
                        $article = Articles::getArticleById($id);

                        if (!$image) { // Если фото не менялось, берем старое.
                            $image = $article->image;
                            $thumbnail = $article->thumbnail;
                        }
                    } else { // Создание новой статьи
                        $article = new Articles();
                        $newArticle = true; // Рассылка при добавлении новой статьи: флаг $newArticle
                    }

                    $article->title = $articleTitle;
                    $article->people = $people;
                    $article->duration = $duration;
                    $article->description = $description;
                    $article->author = $author;
                    $article->subtitle = $subtitle;
                    $article->link = $link;
                    $article->content = $content;
                    $article->image = $image ? $image : DEFAULT_ARTICLE_IMAGE;
                    $article->thumbnail = $thumbnail ? $thumbnail : DEFAULT_ARTICLE_IMAGE;

                    $article->save();

                    if ($article->id) { // Добавление новых связей статья-метод
                        $id = $article->id;

                        // Удалить старые связи статья-метод, если они есть--------
                        ArticleMethods::where('id_article', $id)->delete();

                        foreach ($articleMethods as $method) { // Внести новые связи статья-метод
                            ArticleMethods::upsert(
                                [
                                    'id_article' => $article->id,
                                    'id_method' => $method
                                ],
                                [],
                                []
                            );
                        }
                        $success = 'Статья успешно добавлена/изменена!';
                        // Рассылка при добавлении новой статьи
                        if ($newArticle) {
                            $users = Users::getSubscribedUsers(); // Пользователи, подписанные на рассылку

                            $subject = 'На сайте добавлена новая статья: "' . $article->title . '".'; // Заголовок письма: На сайте добавлена новая запись: “#Название новой статьи#”
                            $message = 'Новая статья: ' // Содержимое письма
                                . $article->title
                                . ', Краткое описание статьи: '
                                . $article->description; // Краткое описание статьи

                            $link = DIRECTORY_SEPARATOR . $_SERVER["HTTP_HOST"] . DIRECTORY_SEPARATOR . ARTICLE . DIRECTORY_SEPARATOR . $article->id; // Ссылка на страницу новой статьи
                            $unsubscribe = UNSUBSCRIBE; // Ссылка на страницу отписки

                            foreach ($users as $user) { // Все, кто подписан - TODO: сделать метод на запрос
                                Post::mailing($user->email, $subject, $message, $link, $unsubscribe);
                            }

                            header('Location: /admin-cms/' . $article->id); // Перегружаем CMS с новыми данными для предотвращения переотправки формы
                        }
                    } else {
                        $success = 'Статья не была добавлена/изменена! Обратитесь к Администратору!';
                    }
                }
            }

            return new AdminView(
                'admin-cms',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'id' => $id,
                    'articleTitle' => $articleTitle ?? '',
                    'subtitle' => $subtitle ?? '',
                    'people' =>  $people ?? '',
                    'duration' => $duration ?? '',
                    'description' => $description ?? '',
                    'author' => $author ?? '',
                    'link' => $link ?? '',
                    'articleMethods' => $articleMethods ?? [],
                    'content' => $content ?? '',
                    'image' => $image ?? '',
                    'success' => $success,
                    'errors' => $errors
                ]
            );
        } else {
            header('Location: /');
        }
    }

    /**
     * Вывод страницы-сообщения об удалении статьи.
     *
     * @return AdminView
     */
    public function articleDelete($success = 0)
    {
        if (isset($_SESSION['user']['id']) && in_array($_SESSION['user']['role'], [ADMIN, CONTENT_MANAGER])) { // Доступ разрешен только админу и контент-менеджеру

            return new AdminView('article-delete', ['title' => 'Page deleted', 'success' => $success]);
        } else {
            header('Location: /');
        }
    }
}
