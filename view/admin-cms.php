<?php

use App\Components\Helper;
use App\Model\Methods;
use App\Model\Articles;
use App\Model\ArticleMethods;

include 'layout/admin_header.php';
include 'layout/admin_title.php';
?>

<div class="container">
  <!-- <br> -->
  <div class="signup-form">
    <!-- <h2><?= $title ?></h2> -->
    <?php
    if ($id) { // Если это редактирование, то загружаем данные из БД
      $articleMethods = ArticleMethods::getMethodsByArticleId($id); // Все связи статьи и методов.
      $article = Articles::getArticleById($id); // Данные статьи
      $articleTitle = $article->title;
      $subtitle = $article->subtitle;
      $people = $article->people;
      $duration = $article->duration;
      $description = $article->description;
      $author = $article->author;
      $link = $article->link;
      $content = $article->content;
      $image = $article->image;
      $thumbnail = $article->thumbnail;
    }
    ?>

    <form action="" enctype="multipart/form-data" id="loadArticle" method="post">
      <div class="row">
        <?php
        if (isset($result) && $result) { ?>
          <h4 class='font-success'>Статья успешно изменена!</h4>
        <?php
        } elseif (isset($result) && !$result) { ?>
          <h4 class='font-error'>Статья не была добавлена/изменена! Обратитесь к Администратору!</h4>
        <?php   } ?>
      </div>
      <!-- Горизонтальная карточка от Bootstrap-->
      <div class="card mt-3 article">
        <div class="row no-gutters">

          <div class="col-md-8 Method_Obl">
            <img src="<?php printf('%s', $image ? DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $image : DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . DEFAULT_ARTICLE_IMAGE); ?>" class="card-img" alt="Photo">
            <p class="card-text">
              <label class="form-label" for="upload">Выберите изображение для статьи:</label>
              <input type="file" id="inputFile" class="custom-file-input " multiple name="myfile" accept="image/png, image/jpeg, image/jpg">
              <label class="form-label
                        <?php if (isset($errors['file'])) : ?>
                          border-error font-error
                        <?php endif ?>
                      " for="upload">
                <?php
                if (isset($errors['file'])) {
                ?>
                  <ul>
                    <?php
                    foreach ($errors['file'] as $error) {
                      printf('<li class="font-error"> %s </li>', $error);
                    }
                    ?>
                  </ul>
                <?php
                } else {
                  printf(' %s', 'Файл не более ' . Helper::formatSize(FILE_SIZE));
                }
                ?>
              </label>
            </p>
          </div>

          <div class="col-md-4 MethodOpis_Block">
            <div class="card-body ">
              <div class="MMName">Заголовок*:
                <input type="text" class="form-control 
                          <?php
                          if ($errors['articleTitle']) : ?>
                            border-error
                          <?php endif ?>
                          " id="articleTitle" name="articleTitle" required placeholder="Название метода" value="<?php printf('%s', $articleTitle ?? ''); ?>">
              </div>
              <div class="font-error">
                <?php
                printf(' %s', $errors['articleTitle'] ?? '');
                ?>
              </div>

              <div class="MVName">Подзаголовок:
                <input type="text" class="form-control 
                          <?php if ($errors['subtitle']) : ?>
                            border-error
                          <?php endif ?>
                          " id="subtitle" name="subtitle" placeholder="Пояснение к заголовку" value="<?php printf('%s', $subtitle ?? ''); ?>">
              </div>
              <div class="font-error">
                <?php
                printf(' %s', $errors['subtitle'] ?? '');
                ?>
              </div>

              <div class="MPeople">На сколько человек*:
                <input type="text" class="form-control 
                          <?php if ($errors['people']) : ?>
                            border-error
                          <?php endif ?>
                          " id="people" name="people" required placeholder="5-15 человек" value="<?php printf('%s', $people ??  ''); ?>">
              </div>
              <div class="font-error">
                <?php
                printf(' %s', $errors['people'] ?? '');
                ?>
              </div>

              <div class="MHours">Длительность*:
                <input type="text" class="form-control 
                          <?php if ($errors['duration']) : ?>
                            border-error
                          <?php endif ?>
                          " id="duration" name="duration" required placeholder="1-2 часа" value="<?php printf('%s', $duration ?? ''); ?>">
              </div>
              <div class="font-error">
                <?php
                printf(' %s', $errors['duration'] ?? '');
                ?>
              </div>

              <div class="MIBlock">Назначение описываемого метода*:
                <input type="text" class="form-control 
                          <?php if ($errors['description']) : ?>
                            border-error
                          <?php endif ?>
                          " id="description" name="description" required placeholder="" value="<?php printf('%s', $description ?? ''); ?>">
              </div>
              <div class="font-error">
                <?php
                printf(' %s', $errors['description'] ?? '');
                ?>
              </div>
              <br>
            </div>
          </div>
        </div>

      </div>
      <p></p><br>
      <div class="row px-5 pt-4 ShadowBig">
        <div class="Redactor col-md-6">Автор*:
          <input type="text" class="form-control 
              <?php if ($errors['author']) : ?>
                border-error
              <?php endif ?>
              ?>
              " id="author" name="author" placeholder="" value="<?php printf('%s', $author ?? ''); ?>">
          <div class="font-error">
            <?php
            printf(' %s', $errors['author'] ?? '');
            ?>
          </div>
        </div>

        <div class="Redactor col-md-6">Ссылка на страницу автора:
          <input type="text" class="form-control 
              <?php if ($errors['link']) : ?>
                border-error
              <?php endif ?>
              " id="link" name="link" placeholder="" value="<?php printf('%s', $link ?? ''); ?>">
          <div class="font-error">
            <?php
            printf(' %s', $errors['link'] ?? '');
            ?>
          </div>
        </div>
      </div>
      <br>
      <br>
      <div>* - поля обязательные для заполнения</div>
      <br>
      <div class="row px-5">
        <div class="col-md-12">
          Выберите разделы, к которым может принадлежать метод:
        </div>

        <div class="MMarkers">
        </div>
        <!-- Меню типов методов -->
        <div class="container-fluid pl-3 pt-2 pb-2 method_menu">
          <div class="row">
            <?php foreach (Methods::all() as $method) :  // Метод модели all получит все записи из связанной с моделью таблицы БД 
            ?>
              <div class="col-6 col-sm-3 col-md">
                <div class="Mbt" style="background-image: url('<?= '/' . IMG . '/' . $method->image; ?>'); background-position: left top; background-repeat: no-repeat; cursor:pointer"><?= $method->name ?>
                  <br>
                  <input class="form-check-input" type="checkbox" name="articleMethods[]" value="<?php printf('%s', $method->id ?? 9); ?>" <?php if (in_array($method->id, $articleMethods)) : ?> checked <?php endif ?>>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        </div>

        <div class="col-md-12">
          <div class="Ozg">Описание метода:</div>
          <div class="Otxt">
            <textarea class="form-control" id="content" name="content" rows="12"><?php printf('%s', $content ?? ''); ?></textarea>
          </div>
        </div>
        <div class="col-md-12"><br>
          <button class="btn btn-outline-primary" type="submit" name="submit" id="submit">Сохранить изменения</button>
          <?php
          if ($id) : ?>
            <a href="/article-delete/<?= $id ?>"><button type="button" class="btn btn-outline-danger"> Удалить статью </button></a>
          <?php endif ?>
        </div>

      </div>
    </form>
    <br />
    <br />
  </div>
</div>

<?php
include 'layout/admin_footer.php';
