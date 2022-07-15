<?php

namespace App\Controllers\Admin;

use \SplFileInfo;
use App\Components\Menu;
use App\Components\Pagination;
use App\Components\SimpleImage;
use App\Model\Articles;
use App\Model\ArticleMethods;
use App\Model\Comments;
use App\Model\Post;
use App\Validator\ArticleValidator;
use App\Validator\CommentValidator;
use App\View\AdminView;

/**
 * Класс ArticleController - контроллер для работы со статьями в админке
 * @package App\Controllers\Admin
 */
class ArticleController extends \App\Controllers\AbstractPrivateController
{
    /**
     * Вывод страницы 'Управление статьями'
     *
     * @return AdminView
     */
    public function adminArticles()
    {
        if (in_array($this->user->role, [ADMIN, CONTENT_MANAGER])) {
            $paginationData = $this->getPaginationData(Articles::class, AdminView::class, 'admin-articles', '~admin-articles/' . PAGINATION_PAGE . '([0-9]+)~');

            return new AdminView(
                'admin-articles',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'articles' => Articles::getArticles($paginationData['limit'], $paginationData['page']), // Статей для вывода на страницу
                    'pagination' => new Pagination($paginationData['total'], $paginationData['page'], $paginationData['limit'], PAGINATION_PAGE), // Постраничная навигация
                    'total' =>  $paginationData['total'], // Всего товаров в БД
                    'limit' =>  $paginationData['limit'], //  Количество товаров на странице
                    'selected' =>  $paginationData['selected'], // Настройка количества товаров на странице
                    'errors' => $errors ?? null
                ]
            );
        } else {
            $this->redirect('/lk');
        }
    }

    /**
     * Вывод страницы 'Управление комментариями'
     *
     * @return AdminView
     */
    public function adminComments()
    {
        if (in_array($this->user->role, [ADMIN, CONTENT_MANAGER])) {

            $paginationData = $this->getPaginationData(Comments::class, AdminView::class, 'admin-comments', '~admin-comments/' . PAGINATION_PAGE . '([0-9]+)~');

            if (isset($_POST['submit'])) {
                $id = $_POST['id'] ?? 0;
                $approve = $_POST['approve'] ?? 0;
                $deny = $_POST['deny'] ?? 0;

                $errors = CommentValidator::approveValidate($id, $approve, $deny);

                if (!$errors) {
                    Comments::changeComment($id, $approve, $deny);
                    $this->redirect('/admin-comments/' . PAGINATION_PAGE . $paginationData['page']);
                } else {
                    $errors[] = 'Ошибка управления комментарием. Обратитесь к администртору!';
                }
            }

            return new AdminView(
                'admin-comments',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'comments' => Comments::getComments($paginationData['limit'], $paginationData['page']), // Комментарии для вывода на страницу
                    'pagination' => new Pagination($paginationData['total'], $paginationData['page'], $paginationData['limit'], PAGINATION_PAGE), // Постраничная навигация
                    'total' =>  $paginationData['total'], // Всего товаров в БД
                    'limit' =>  $paginationData['limit'], //  Количество товаров на странице
                    'selected' =>  $paginationData['selected'], // Настройка количества товаров на странице
                    'errors' => $errors ?? null
                ]
            );
        } else {
            $this->redirect('/lk');
        }
    }

    /**
     * Вывод страницы 'Управление статичными страницами' - создание/редактирование статьи
     *
     * @return AdminView
     */
    public function adminCMS($id = 0)
    {
        if (in_array($this->user->role, [ADMIN, CONTENT_MANAGER])) {

            if (isset($_POST['submit'])) {
                $articleTitle = $_POST['articleTitle'] ?? '';
                $subtitle = $_POST['subtitle'] ?? '';
                $people = $_POST['people'] ?? '';
                $duration = $_POST['duration'] ?? '';
                $description = $_POST['description'] ?? '';
                $author = $_POST['author'] ?? '';
                $link = $_POST['link'] ?? '';
                $articleMethods = $_POST['articleMethods'] ?? [];
                $content = $_POST['content'] ?? '';

                $errors = ArticleValidator::articleValidate(
                    $articleTitle,
                    $subtitle,
                    $people,
                    $duration,
                    $description,
                    $author,
                    $link,
                    $articleMethods,
                    $content,
                );

                if ($_FILES['myfile']['name'] != '') { // Проверка на наличие файла для загрузки
                    $types = include(CONFIG_DIR . IMAGE_TYPES); // Допустимые типы файла изображения

                    $fileError = SimpleImage::imageFileValidation($types, FILE_SIZE, $_FILES); // Валидация файла изображения

                    if ($fileError) {
                        $errors['file'] = $fileError; // Если валидация не прошла, то добавляем её ошибки
                    }

                    if (!$errors) {
                        $myfile = new SplFileInfo($_FILES['myfile']['name']);
                    }
                }

                if (!$errors) { // Если ошибок нет, то добавляем данные.
                    $newArticle = false;

                    if ($id) { // Редактирование существующей статьи
                        $article = Articles::getArticleById($id);
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

                    $article->save(); // Либо новая статья, либо редактирование

                    if ($article->id) {
                        $id = $article->id; // Если это новая статья, то id-статьи создается. Если это редактирование существующей, то без изменений - далее используется в return

                        ArticleMethods::where('id_article', $id)->delete(); // Удалить старые связи статья-метод, если они есть

                        foreach ($articleMethods as $method) { // Внести новые связи статья-метод
                            ArticleMethods::upsert(
                                [
                                    'id_article' => $id,
                                    'id_method' => $method
                                ],
                                [],
                                []
                            );
                        }

                        if (isset($myfile)) { // Есть файл для загрузки
// Варианты: 
// 1. Новая статья: 1.1 без фото - Ок, 1.2 с фото - Ок
// 2. Редактирование существующей статьи: 1.1 без фото - Ок, 1.2 с фото - Ок

                            // ПЕРЕД ЗАГРУЗКОЙ удаляем старый файл на сервере, т.к. м.б. разные расширения и тогда на сервере будет 2 и более файла с одним именем и разными рсширениями.
                            if (DEFAULT_ARTICLE_IMAGE != $article->image) { // Если это не заставка  и это не новая стаья- только для редактирования, чтобы не удалить заставку.
                                if (file_exists(IMG_STORAGE . $article->image) && is_file(IMG_STORAGE . $article->image)) {
                                    unlink(IMG_STORAGE . $article->image); 
                                }

                                if (file_exists(IMG_STORAGE . $article->thumbnail && is_file(IMG_STORAGE . $article->thumbnail))) {
                                    unlink(IMG_STORAGE . $article->thumbnail);
                                }
                            }

                            $image = 'image_article_' . rand(1,999) . '-'. $id . '.' . $myfile->getExtension();

                            if (move_uploaded_file($_FILES['myfile']['tmp_name'], IMG_STORAGE . $image)) {
                                $thumbnail = 'thumbnail_' . $image;
 // Изменение размера изображения для Главной.
                                $thumbnailObj = new SimpleImage();
                                $thumbnailObj->load(IMG_STORAGE . $image);
                                $thumbnailObj->resize(490, 280);
                                $thumbnailObj->save(IMG_STORAGE . $thumbnail);
 // Изменение размера изображения для страницы статьи.
                                $imageObj = new SimpleImage();
                                $imageObj->load(IMG_STORAGE . $image);
                                $imageObj->resize(1100, 620);
                                $imageObj->save(IMG_STORAGE . $image);
                            } else {
                                $errors['file']['LoadServerError'] = 'Файл ' . $$myfile->getFilename() . ' не был загружен на сервер';
                            }
                        }

                        if ($newArticle) {
                            $article->image = $image ?? DEFAULT_ARTICLE_IMAGE;
                            $article->thumbnail = $thumbnail ?? DEFAULT_ARTICLE_THUMBNAIL;
                            $article->save();

                            Post::mailing($article); // Рассылка при добавлении новой статьи

                            $this->redirect('/new-article');
                        } else { // Редактирование



// echo '<script>alert("image")</script>';
// echo 'image befor save: ';
// var_dump($image); // string(21) "image_article_152.png" - New
// echo '<br>';
// echo 'article->image befor save: ';
// var_dump($article->image); // string(21) "image_article_152.jpg" - Old

                            $article->image = $image ?? $article->image;
                            $article->thumbnail = $thumbnail ?? $article->thumbnail;
                            $article->save();
// echo '<br>';
// echo 'article->image after save: ';
// var_dump($article->image); // string(21) "image_article_152.jpg" - Old
                            $result = true;
                        }
                    } else {
                        $result = false;
                    }
                }
            }

            return new AdminView(
                'admin-cms',
                [
                    'title' => Menu::showTitle(Menu::getAdminMenu()),
                    'id' => $id ?? 0,
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
                    'result' => $result ?? null,
                    'errors' => $errors ?? null
                ]
            );
        } else {
            $this->redirect('/lk');
        }
    }

    /**
     * Вывод страницы-сообщения об удалении статьи.
     *
     * @return AdminView
     */
    public function articleDelete($id)
    {
        if (in_array($this->user->role, [ADMIN, CONTENT_MANAGER])) {

            if (Articles::where('id', $id)->first()) {
                $success = Articles::where('id', $id)->delete();
            } else {
                $errors[] = 'Ошибка в данных статьи. Обратитесь к администратору';
            }

            return new AdminView(
                'article-delete',
                [
                    'title' => 'Page deletion',
                    'id' => $id ?? null,
                    'success' => $success ?? null,
                    'errors' => $errors ?? null
                ]
            );
        } else {
            $this->redirect('/lk');
        }
    }

    /**
     * Вывод страницы-сообщения о создании новой статьи.
     *
     * @return AdminView
     */
    public function newArticle()
    {
        if (in_array($this->user->role, [ADMIN, CONTENT_MANAGER])) {

            return new AdminView(
                'new-article',
                [
                    'title' => 'New article',
                ]
            );
        } else {
            $this->redirect('/lk');
        }
    }
}
