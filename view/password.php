<?php
include 'layout/header.php';
?>

<div class="container">
  <br>
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <?php include 'errors/errors-list.php'; ?>
      <div class="signup-form">
        <h2>Смена пароля </h2>
        <?php
        if (isset($success)) : ?>
          <h4 class='font-success'> <?= $success ?> </h4>
        <? endif ?>
        <form action="" method="post">
          <div class="mb-3">
            <label for="old_password" class="form-label">Старый пароль</label>
            <input type="password" class="form-control 
              <?php if (isset($old_password) && isset($errors['wrongPassword'])) : ?>
                border-error
              <?php endif ?>
              " id="old_password" name="old_password" value="<?= $old_password ?>">
          </div>

          <div class="mb-3">
            <label for="new_password" class="form-label">Новый пароль</label>
            <input type="password" class="form-control 
              <?php if (isset($old_password) && isset($errors['checkPassword'])) : ?>
                border-error
              <?php endif ?>
              " id="new_password" name="new_password" value="<?= $new_password ?>">
          </div>

          <div class="mb-3">
            <label for="confirm_password" class="form-label">Ещё раз новый пароль</label>
            <input type="password" class="form-control
              <?php if (isset($confirm_password) && isset($errors['comparePasswords'])) : ?>
                border-error
              <?php endif ?>
              " id="confirm_password" name="confirm_password" value="<?= $confirm_password ?>">
          </div>
          <div class="mb-3">
            <button class="btn btn-outline-primary" type="submit" name="submit" id="button-addon1">Сменить пароль</button>
          </div>
        </form>
      </div>
      <br />
      <br />
    </div>
  </div>
</div>

<?php
include 'layout/footer.php';
