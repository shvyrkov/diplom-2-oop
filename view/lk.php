<?php

use App\Components\Helper;

include 'layout/header.php';
?>
<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <ul>
        <?php
        if (isset($errors['user'])) {
          printf('%s', Helper::getErrors($errors['user']));
        }
        ?>
      </ul>
    </div>
  </div>
</div>
<div class="container">
  <h1><?= $title ?></h1>
  <form action="" enctype="multipart/form-data" id="loadUser" method="post">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-4 padding-right">
        <div class="signup-form">
          <div class="mb-3">
            <label for="name_lk" class="form-label">Имя</label>
            <input type="text" class="form-control 
                <?php if (isset($errors['name'])) : ?>
                  border-error
                <?php endif ?>
                " id="name_lk" name="name" required placeholder="name" value="<?php printf('%s', $name ? $name : $user->name); ?>">
            <span class="font-error">
              <?php printf(' %s', $errors['name'] ?? ''); ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="email_lk" class="form-label">Email</label>
            <input type="email" class="form-control
                <?php if (isset($errors['email'])) : ?>
                   border-error 
                <?php endif ?>
                " id=" email_lk" name="email" required placeholder="name@example.com" value="<?php printf('%s', $email ? $email : $user->email); ?>">
            <span class="font-error">
              <?php
              printf(' %s', $errors['email'] ?? '');
              ?>
            </span>
          </div>
          <div class="mb-3">
            <?php
            foreach ($roles as $role) {
              if ($user->role == $role['id']) {
                $userRole = $role['name'];
              }
            }
            ?>
            <p>Ваш уровень на сайте: <b><?php printf('%s', $userRole ?? ''); ?></b></p>
          </div>
          <div class="mb-3">
            <label for="about_me_lk" class="form-label">О себе</label>
            <textarea class="form-control <?php if ($errors['aboutMe']) : ?> border-error <?php endif ?>" id="about_me_lk" name="aboutMe" rows="3"><?php printf('%s', $aboutMe ?  $aboutMe : $user->aboutMe); ?></textarea>
            <span class="font-error">
              <?php
              printf(' %s', $errors['aboutMe'] ?? '');
              ?>
            </span>
          </div>

          <div class="mb-3">
            <button class="btn btn-outline-primary" type="submit" name="submit" id="submit">Сохранить изменения</button>
          </div>

          <div class="mb-3">
            <a href="password"><button class="btn btn-outline-secondary" type="button" name="pwd" id="pwd">Сменить пароль</button></a>
          </div>

          <div class="mb-3">
            <?php
            if (!$user->subscription) { ?>
              <a href="subscription"><button type="button" class="btn btn-outline-warning">Подписаться на рассылку</button></a>
            <?php
            } else { ?>
              <a href="unsubscribe"><button type="button" class="btn btn-outline-danger">Отписаться от рассылки</button></a>
            <?php } ?>
          </div>

          <div class="mb-3">
            <?php
            if (in_array($user->role, [ADMIN, CONTENT_MANAGER])) :
            ?>
              <a href="admin"><button class="btn btn-outline-dark" type="button" name="admin" id="admin">Админка</button></a>
            <?php endif ?>
          </div>
        </div>
      </div>
      <div class="col-sm-1 col-sm-offset-4 padding-right">
      </div>
      <div class="col-sm-4 col-sm-offset-4 padding-right">
        <div class="card
          <?php
          if (isset($errors['file'])) : ?>
            border-error
          <?php endif ?> ">
          <img src="<?= AVATARS . $user->avatar ?>" class="card-img-top" alt="avatar">
          <div class="card-body">
            <h5 class="card-title"></h5>
            <p class="card-text"></p>
            <input type="file" id="inputFile" class="custom-file-input " multiple name="myfile" accept="image/png, image/jpeg, image/jpg">
            <label class="form-label
              <?php
              if (isset($errors['file'])) : ?>
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
                printf(' %s', 'Выберите файл не более ' . Helper::formatSize(FILE_SIZE));
              }
              ?>
            </label>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<?php
include 'layout/footer.php';
