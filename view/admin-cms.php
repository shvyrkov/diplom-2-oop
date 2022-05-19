<?php
use App\Model\Methods;
use App\Model\Articles;
use App\Model\ArticleMethods;
use App\Model\Users;

include 'layout/admin_header.php';
?>

<div class="container">
    <br>
    <div class="signup-form"><!--sign up form-->
      <h2><?=$title ?></h2>
      <?php
      $articleMethods = [];

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
            if ($change && $result) {
              echo "<h4 class='font-success'>Статья успешно добавлена/изменена!</h4>";
            } 
            if($change && !$result) {
                echo "<h4 class='font-fail'>Статья не была добавлена/изменена! Обратитесь к Администратору!</h4>";
            }
            ?>
            <div class="col-sm-8 col-sm-offset-4 padding-right">
                <!-- <?php if (isset($errors) && is_array($errors)): ?>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li class="font-error"> <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>  -->
            </div>
        </div>
    <!-- Горизонтальная карточка от Bootstrap-->    
        <div class="card mt-3 article">
            <div class="row no-gutters">
                
                <div class="col-md-8 Method_Obl" >
                  <img src="<?php printf('%s', $image ? DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $image : DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . DEFAULT_ARTICLE_IMAGE); ?>" class="card-img" alt="Photo">
                  <p class="card-text">
                      <label class="form-label" for="upload">Выберите изображение для статьи:</label>
                      <input type="file" id="inputFile" class="custom-file-input " multiple name="myfile" accept="image/png, image/jpeg, image/jpg">
                      <label class="form-label
                        <?php
                        if(isset($errors['file'])) {
                          echo "border-error font-error";
                        }
                        ?>
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
                          printf(' %s', 'Файл не более ' . Users::formatSize(FILE_SIZE));
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
                          if($errors['articleTitle']) { echo "border-error"; }
                          ?>
                          " id="articleTitle" name="articleTitle" required placeholder="Название метода" value="<?php printf('%s', $articleTitle ?? ''); ?>">
                        </div>
                        <div class="font-error">
                            <?php
                            printf(' %s', $errors['articleTitle'] ?? '');
                            ?>
                        </div>

                        <div class="MVName">Подзаголовок:
                          <input type="text" class="form-control 
                          <?php
                          if($errors['subtitle']) { echo "border-error"; }
                          ?>
                          " id="subtitle" name="subtitle" placeholder="Пояснение к заголовку" value="<?php printf('%s', $subtitle ?? ''); ?>">
                        </div>
                        <div class="font-error">
                            <?php
                            printf(' %s', $errors['subtitle'] ?? '');
                            ?>
                        </div>

                        <div class="MPeople">На сколько человек*:
                          <input type="text" class="form-control 
                          <?php
                          if($errors['people']) { echo "border-error"; }
                          ?>
                          " id="people" name="people" required placeholder="5-15 человек" value="<?php printf('%s', $people ??  ''); ?>">
                        </div>
                        <div class="font-error">
                            <?php
                            printf(' %s', $errors['people'] ?? '');
                            ?>
                        </div>

                        <div class="MHours">Длительность*:
                          <input type="text" class="form-control 
                          <?php
                          if($errors['duration']) { echo "border-error"; }
                          ?>
                          " id="duration" name="duration" required placeholder="1-2 часа" value="<?php printf('%s', $duration ?? ''); ?>">
                        </div>
                        <div class="font-error">
                            <?php
                            printf(' %s', $errors['duration'] ?? '');
                            ?>
                        </div>

                        <div class="MIBlock">Назначение описываемого метода*:
                          <input type="text" class="form-control 
                          <?php
                          if($errors['description']) { echo "border-error"; }
                          ?>
                          " id="description" name="description" required placeholder="" value="<?php printf('%s', $description ?? ''); ?>">
                        </div>
                        <div class="font-error">
                            <?php
                            printf(' %s', $errors['description'] ?? '');
                            ?>
                        </div>
                    </div><!-- card-body -->
                </div><!-- col-md-4 MethodOpis_Block -->
            </div><!-- row no-gutters-->
        </div><!-- card mt-3 article -->
        <p></p>
        <div class="row px-5 pt-4 ShadowBig">
            <div class="Redactor col-md-6">Автор*: 
              <input type="text" class="form-control 
              <?php
              if($errors['author']) { echo "border-error"; }
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
              <?php
              if($errors['link']) { echo "border-error"; }
              ?>
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
                <?php

                foreach (Methods::all() as $method) {  // Метод модели all получит все записи из связанной с моделью таблицы БД
                ?>
                  <div class="col-6 col-sm-3 col-md">
                      <div  class="Mbt" style="background-image: url('<?='/' . IMG . '/' .$method->image; ?>'); background-position: left top; background-repeat: no-repeat; cursor:pointer"><?=$method->name ?>
                        <br>
                        <input class="form-check-input" type="checkbox" name="methods[]" value="<?php printf('%s', $method->id ?? 9); ?>"
                        <?php if (in_array($method->id, $articleMethods)): ?>
                          checked
                        <?php endif ?>
                        >
                      </div>
                  </div>
                <?php
                }
                ?>
                </div><!-- row -->
            </div><!-- container -->

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
                <input type="text" hidden class="form-control" name="id" value="<?=$id; ?>">
                <button type="submit" name="delete" class="btn btn-outline-danger">Удалить статью</button>
            <?php endif ?>
            </div>
            
        </div><!-- row -->
      </form>
      <br/>
      <br/>
    </div><!--/sign up form-->
</div><!-- container -->

<?php
include 'layout/admin_footer.php';
