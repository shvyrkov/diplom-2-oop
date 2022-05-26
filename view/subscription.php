<?php
include 'layout/header.php';
?>
<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
                <?php if (isset($errors) && is_array($errors)): ?>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li class="font-error"> <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
    </div>
  </div>
</div>
<?php 
// echo "<pre>";
// echo '<br>!isset($_SESSION["user"]["subscription"]: ';
// var_dump(!isset($_SESSION['user']['subscription']));
// echo '<br>isset($_SESSION["user"]: ';
// var_dump(isset($_SESSION['user']));

// echo "<br>user: ";
// if (is_object($user)) {
//     var_dump($user->subscription);
// } else {
//     var_dump($user);
// }
// echo "<br>result: ";
// var_dump($result);
// echo "<br>errors: ";
// var_dump($errors );
// echo "</pre>";
// Условия вывода поля e-mail:
  // Пользователь неавторизован - !isset($_SESSION['user'])
  // и неподписан - $result
  // или ошибка - $errors

if (!isset($_SESSION['user']) // Пользователь неавторизован 
    && !$result               // и подписка не прошла (и для авторизованных) - $result
    || $errors                // или ошибка
) { 
?>
  <div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-4 padding-right">
            <div class="signup-form"><!--sign up form-->
              <form action="" method="post">
                <div class="mb-3">
                  <label for="email" class="form-label">Введите Ваш email для получения уведомлений о появлении новой статьи на сайте:</label>
                  <input type="email" class="form-control
                  <?php
                  if($errors['checkEmail'] || $errors['checkEmailExists']) {
                    echo "border-error";
                  }
                  ?>
                  " id="email" name="email" required placeholder="name@example.com" value="<?php printf('%s', $email ?? ''); ?>">
                  <span class="font-error">
                    <?php
                    // printf(' %s', $errors['checkEmail'] ?? '');
                    // printf(' %s', $errors['checkEmail'] ?? '');
                    // printf(' %s', $errors['checkEmailExists'] ?? '');
                    ?>
                  </span>
                </div>

                <div class="mb-3">
                  <button type="submit" name="subscribeNotAuthUser" class="btn btn-primary">Подписаться на рассылку.</button>
                </div>
              </form>
            </div><!--/sign up form-->
            <br/>
            <br/>
        </div>
    </div>
  </div>
<?php } else { ?>
  <div class="container">
      <h3>Вы подписаны на рассылку!</h3>
  </div>
<?php
}

include 'layout/footer.php';
