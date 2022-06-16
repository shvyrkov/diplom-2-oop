<?php
include 'layout/header.php';
?>

<div class="container">
  <br>
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <?php if (isset($errors) && is_array($errors)) : ?>
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li class="font-error"> <?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="signup-form">
        <h2>Вход </h2>
        <form action="" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control
                              <?php if ($errors['email'] || $errors['wrongData']) : ?>
                                border-error
                              <?php endif ?>
                              " id="email" name="email" placeholder="name@example.com" value="<?= $email ?>">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control
                              <?php if ($errors['wrongData']) : ?>
                                border-error
                              <?php endif ?>
                              " id="password" name="password" placeholder="password" value="<?= $password ?>">
          </div>
          <div class="mb-3">
            <button class="btn btn-outline-primary" type="submit" name="submit" id="button-addon1">Войти</button>
            <a href="registration"><button class="btn btn-outline-secondary" type="button" name="reg" id="button-addon2">Регистрация</button> </a>
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
