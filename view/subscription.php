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
<?php if (!isset($_SESSION['user'])) { ?>
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
                    printf(' %s', $errors['checkEmail'] ?? '');
                    printf(' %s', $errors['checkEmailExists'] ?? '');
                    ?>
                  </span>
                </div>

                <div class="mb-3">
                  <button type="submit" name="subscription" value="1" class="btn btn-primary">Подписаться на рассылку.</button>
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
