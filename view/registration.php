<?php
include 'layout/header.php';
?>
<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <?php if (isset($errors['no_data'])) : ?>
        <ul>
          <li class="font-error"> <?php echo $errors['no_data']; ?></li>
        </ul>
      <?php endif ?>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-4 col-sm-offset-4 padding-right">
      <div class="signup-form">
        <h2>Регистрация </h2>
        <form action="" method="post">
          <div class="mb-3">
            <label for="name" class="form-label">Имя
            </label>
            <input type="text" class="form-control
                    <?php
                    if (isset($errors['checkName']) || isset($errors['checkNameExists'])) : ?>
                      border-error
                    <?php endif ?>
                    " id="name" name="name" required placeholder="name" value="<?php printf('%s', $name ?? 'Введите имя'); ?>">
            <span class="font-error">
              <?php
              printf(' %s', $errors['checkName'] ?? '');
              printf(' %s', $errors['checkNameExists'] ?? '');
              ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control
                    <?php
                    if (isset($errors['checkEmail']) || isset($errors['checkEmailExists'])) : ?>
                      border-error
                    <?php endif ?>
                    " id="email" name="email" required placeholder="name@example.com" value="<?php printf('%s', $email ?? 'Введите email'); ?>">
            <span class="font-error">
              <?php
              printf(' %s', $errors['checkEmail'] ?? '');
              printf(' %s', $errors['checkEmailExists'] ?? '');
              ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control
                    <?php
                    if (isset($errors['checkPassword'])) : ?>
                      border-error
                    <?php endif ?>
                    " id="password" name="password" required placeholder="password" value="<?php printf('%s', $password ?? 'Введите пароль'); ?>">
            <span class="font-error">
              <?php
              printf(' %s', $errors['checkPassword'] ?? '');
              ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Подтверждение пароля</label>
            <input type="password" class="form-control
                    <?php
                    if (isset($errors['comparePasswords'])) : ?>
                      border-error
                    <?php endif ?>
                    " id="confirm_password" name="confirm_password" required placeholder="confirm password" value="<?php printf('%s', $confirm_password ?? 'Подтвердите пароль'); ?>">
            <span class="font-error">
              <?php
              printf(' %s', $errors['comparePasswords'] ?? '');
              ?>
            </span>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" <?php if ($rules) : ?> checked <?php endif ?> id="rules" name="rules" required>
            <label class="form-check-label" for="flexCheckDefault">
              согласен с <a href="rules">правилами</a> сайта
            </label>
          </div>
          <div class="mb-3">
            <button class="btn btn-outline-primary" type="submit" name="submit" id="button-addon1">Регистрация</button>
            <a href="login"><button class="btn btn-outline-secondary" type="button" name="reg" id="button-addon2">Вход</button> </a>
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
