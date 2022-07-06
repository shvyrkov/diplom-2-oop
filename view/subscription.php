<?php
include 'layout/header.php';
?>
<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <?php include 'errors/errors-list.php'; ?>
    </div>
  </div>
</div>
<?php
if (
  !isset($user) // Пользователь неавторизован 
  && !$result   // и подписка не прошла (и для авторизованных) - $result
  || $errors    // или ошибка
) {
?>
  <div class="container">
    <div class="row">
      <div class="col-sm-10 col-sm-offset-4 padding-right">
        <div class="signup-form">
          <form action="" method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Введите Ваш e-mail для получения уведомлений о появлении новой статьи на сайте:</label>
              <input type="email" class="form-control
                  <?php
                  if ($errors) {
                    echo "border-error";
                  }
                  ?>
                  " id="email" name="email" required placeholder="name@example.com" value="<?php printf('%s', $email ?? ''); ?>">
            </div>
            <div class="mb-3">
              <button type="submit" name="subscribe" class="btn btn-primary">Подписаться на рассылку.</button>
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
    <h3>Вы подписаны на рассылку!</h3>
  </div>
<?php
}

include 'layout/footer.php';
