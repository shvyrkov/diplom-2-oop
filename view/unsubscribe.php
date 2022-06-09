<?php
include 'layout/header.php';
?>
<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <?php if (isset($errors) && is_array($errors)) : ?>
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li class="font-error"> <?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php
if (
  !$result // отдписка не прошла + первичный переход по ссылке из письма рассылки
  || $errors // или ошибка
) {
?>
  <div class="container">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-4 padding-right">
        <br />
        <br />
        <div class="signup-form">
          <form action="" method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Введите Ваш e-mail для прекращения получения уведомлений:</label>
              <input type="email" class="form-control
                  <?php
                  if ($errors['checkEmail'] || $errors['checkEmailExists']) {
                    echo "border-error";
                  }
                  ?>
                  " id="email" name="email" required placeholder="name@example.com" value="<?php printf('%s', $email ?? ''); ?>">
            </div>
            <div class="mb-3">
              <button type="submit" name="unsubscribe" class="btn btn-primary">Отписаться от рассылки.</button>
            </div>
          </form>
        </div>
        <br />
        <br />
      </div>
    </div>
  </div>
<?php } else { ?>
  <div class="container">
    <h3>Вы отписались от рассылки!</h3>
  </div>
<?php
}

include 'layout/footer.php';
