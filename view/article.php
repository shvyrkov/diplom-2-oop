<?php
use App\Model\Articles;
use App\Model\Users;

include 'layout/header.php';
?>


<div class="container-fluid ">
<!-- Горизонтальная карточка от Bootstrap-->    
    <div class="card mt-3 article">
        <div class="row no-gutters">
            <div class="col-md-8 Method_Obl" >
                <img src="<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article[0]->image; ?>" class="card-img" alt="Photo">
            </div>
            <div class="col-md-4 MethodOpis_Block">
                <div class="card-body ">
                    <div class="MMarkers">
                        <?php
                        foreach (Articles::getMethods($id) as $method) { ?>
                            <img  src="<?=DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $method->image; ?>">
                        <?php
                        }
                        ?>
                    </div>
                    <div class="MMName"><?=$title ?></div>
                    <div class="MVName"><?=$article[0]->subtitle ?></div>
                    <div class="MPeople"><?=$article[0]->people ?></div>
                    <div class="MHours"><?=$article[0]->duration ?></div>
                    <div class="MIBlock"><?=$article[0]->description ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row px-5 pt-4 ShadowBig">
        <div class="Redactor">Версия от <?=$article[0]->date ?> — автор 
            <?php
            if ($article[0]->link != '#') {
            ?>
            <a href="<?=$article[0]->link ?>">
            <?php
            }
            echo $article[0]->author;
            if ($article[0]->link != '#') { ?>
            </a>
            <?php } ?>
        </div>
    </div>
    <div class="row px-5">
        <div class="col-md-8">
            <div class="Ozg">Описание метода "<?=$title ?>"</div>
            <div class="Otxt">
                <!-- <pre> -->
                    <?=$article[0]->content ?>
                <!-- </pre> -->
            </div>
        </div>

<!-- Comments --------------------------- -->
        <div class="col-md-4">
            <div class="Ozg">Комментарии</div>
            <div class="row d-flex justify-content-center">
              <div class="col-md-12 col-lg-12">
                <div class="card shadow-0 border" style="background-color: #f0f2f5;">
                  <div class="card-body p-4">
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li class="font-error"> <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <div class="form-outline mb-4">
                      <form action="" id="comment_form" enctype="multipart/form-data" method="post">
                        <input type="text" name="text" class="form-control" placeholder="Type comment..."/>
                        <input type="text" name="userId" hidden value="<?=$_SESSION['user']['id'] ?? '' ?>">
                        <input type="text" name="articleId" hidden value="<?=$id ?? '' ?>">
                        <input type="text" name="role" hidden value="<?=$_SESSION['user']['role'] ?? ''  ?>">
                        <button class="btn btn-outline-primary" name="loadComment" for="addANote">Добавить комментарий</button>
                      </form>
<script type="text/javascript">
// -------------Test: убрать переотправку формы при обновлении страницы --------------
// alert('befor comment_form');
// window.location.reload(); // Перезагрузка и сброс формы - @TODO: убрать как-то в слушатель после нажатия кнопки отправки формы
// создадим пустой объект
// var $data = {};
// переберём все элементы input, textarea и select формы с id="myForm "
// $('#comment_form').find ('input, button').each(function() {
  // добавим новое свойство к объекту $data
  // имя свойства – значение атрибута name элемента
  // значение свойства – значение свойство value элемента
  // $data[this.name] = $(this).val();
  // alert($data[this.name]);
  // $(this).val(''); // Сброс данных формы - сбрасывает, но переотправка срабатывает!
  // $data[this.name] = $(this).val();
  // alert($data[this.name]);
// });
     // var value_1 = $('#comment_form').val(); // Сброс формы - не работает  вообще
     // alert('value_1: ' + value_1);
     // $('#comment_form')[0].reset(); // Сброс формы - не работает  вообще
     // $('#comment_form').reset(); // Сброс формы - не работает  вообще
     // $("#comment_form").trigger('reset'); // Сброс формы - что-то срабатывает, но форму не сбрасывает
// alert('after comment_form');
</script>
                    </div>
                    <?php
                    foreach ($comments as $comment) { 
                        if (($comment->approve // Если коммент утвержден
                            || in_array($_SESSION['user']['role'] ?? NO_USER, [ADMIN, CONTENT_MANAGER]) // или вы имеете право модерировать
                            || (($_SESSION['user']['id'] ?? NO_USER) == $comment->user_id)) // или вы тот, кто написал этот коммент
                            && !$comment->deny) { // Если коммент не отклонен 
                         ?>
                            <div class="card mb-4">
                              <div class="card-body">
                                <p><?=$comment->text ?></p>
                                <div class="d-flex justify-content-between">
                                  <div class="d-flex flex-row align-items-center">
                                    <img
                                      src="<?=AVATARS . Users::getUserById($comment->user_id)->avatar; ?>"
                                      alt="avatar"
                                      width="30"
                                      height="30"
                                    />
                                    <p class="small mb-0 ms-2"><?=Users::getUserById($comment->user_id)->name ?></p>
                                  </div>
                                  <div class="d-flex flex-row align-items-center">
                                    <p class="small text-muted mb-0"><?=$comment->date ?></p>
                                  </div>
                                </div>
                                <br>
                                <?php
                                if (!$comment->approve 
                                    && (in_array($_SESSION['user']['role'] ?? NO_USER, [ADMIN, CONTENT_MANAGER]) 
                                    || (($_SESSION['user']['id'] ?? NO_USER) == $comment->user_id))) {  // Немодерированный коммент виден только админу, контент-менеджеру и тому кто написал
                                ?>
                                <!-- @TODO: form - видна только модераторам -->
                                <p class="text-danger">Коммментарий не утвержден</p>
                                <?php
                                if (!$comment->approve 
                                    && (in_array($_SESSION['user']['role'] ?? NO_USER, [ADMIN, CONTENT_MANAGER]) 
                                    )) { // Утверждать могут только админ и контент-менеджер
                                ?>
                                <form action="" id="approve_form" enctype="multipart/form-data" method="post">
                                    <button class="btn btn-outline-primary" name="approve" value="<?=$comment->id ?>">Утвердить комментарий</button> 
                                    <button class="btn btn-outline-danger" name="deny" value="<?=$comment->id ?>">Отклонить комментарий</button>
                                </form>
                                <?php } } ?>
                            </div>
                          </div>
                        <?php
                        }
                    }
                    ?>
                </div>
              </div>
            </div>
        </div><!-- Comments -->
    </div><!-- row -->
</div><!--container-fluid-->
<br>
<?php
include 'layout/footer.php';
