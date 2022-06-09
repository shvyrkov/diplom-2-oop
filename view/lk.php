<?php

use App\Model\Users;

include 'layout/header.php';
?>

<div class="container">
  <h1><?= $title ?></h1>
  <form action="" enctype="multipart/form-data" id="loadUser" method="post">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-4 padding-right">
        <div class="signup-form">
          <div class="mb-3">
            <label for="name_lk" class="form-label">Имя</label>
            <input type="text" class="form-control 
                <?php
                if ($errors['checkName'] || $errors['checkNameExists']) {
                  echo "border-error";
                }
                ?>
                " id="name_lk" name="name" required placeholder="name" value="<?php printf('%s', $_SESSION['user']['name'] ?? ''); ?>">
            <span class="font-error">
              <?php
              printf('%s', ($name != $_SESSION['user']['name']) ? $name : '');
              printf(' %s', $errors['checkName'] ?? '');
              printf(' %s', $errors['checkNameExists'] ?? '');
              ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="email_lk" class="form-label">Email</label>
            <input type="email" class="form-control
                <?php
                if ($errors['checkEmail'] || $errors['checkEmailExists']) {
                  echo "border-error";
                }
                ?>
                " id="email_lk" name="email" required placeholder="name@example.com" value="<?php printf('%s', $_SESSION['user']['email'] ?? ''); ?>">
            <span class="font-error">
              <?php
              printf('%s', ($email != $_SESSION['user']['email']) ? $email : '');
              printf(' %s', $errors['checkEmail'] ?? '');
              printf(' %s', $errors['checkEmailExists'] ?? '');
              ?>
            </span>
          </div>
          <div class="mb-3">
            <?php
            foreach ($roles as $role) {
              if ($_SESSION['user']['role'] == $role['id']) {
                $userRole = $role['name'];
              }
            }
            ?>
            <p>Ваш уровень на сайте: <b><?php printf('%s', $userRole ?? ''); ?></b></p>
          </div>
          <div class="mb-3">
            <label for="about_me_lk" class="form-label">О себе</label>
            <textarea class="form-control" id="about_me_lk" name="aboutMe" rows="3"><?php printf('%s', $_SESSION['user']['aboutMe'] ?? ''); ?></textarea>
          </div>
          <div class="mb-3">
            <button class="btn btn-outline-primary" type="submit" name="submit" id="submit">Сохранить изменения</button>
            <a href="password"><button class="btn btn-outline-secondary" type="button" name="pwd" id="pwd">Сменить пароль</button></a>
          </div>
          <div class="mb-3">
            <?php
            if (!$_SESSION['user']['subscription']) { ?>
              <button class="btn btn-outline-warning" type="submit" name="subscription" value="1" id="subscriptOn">Подписаться на рассылку</button>
            <?php
            } else { ?>
              <button class="btn btn-outline-danger" type="submit" name="subscription" value="0" id="subscriptOff">Отписаться от рассылки</button>
            <?php } ?>
          </div>
          <div class="mb-3">
            <?php
            if ($_SESSION['user']['role'] <= CONTENT_MANAGER) { // 1 - admin, 2 - content-manager 
            ?>
              <a href="admin"><button class="btn btn-outline-dark" type="button" name="admin" id="admin">Админка</button></a>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="col-sm-1 col-sm-offset-4 padding-right">
      </div>
      <div class="col-sm-4 col-sm-offset-4 padding-right">
        <div class="card
          <?php
          if ($errors['file']) {
            echo "border-error";
          }
          ?>
        ">
          <img src="<?= AVATARS . $_SESSION['user']['avatar'] ?>" class="card-img-top" alt="avatar">
          <div class="card-body">
            <h5 class="card-title"></h5>
            <p class="card-text"></p>
            <input type="file" id="inputFile" class="custom-file-input " multiple name="myfile" accept="image/png, image/jpeg, image/jpg">
            <label class="form-label
              <?php
              if (isset($errors['file'])) {
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
                printf(' %s', 'Выберите файл не более ' . Users::formatSize(FILE_SIZE));
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
