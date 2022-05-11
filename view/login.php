<?php
include 'layout/header.php';
?>

<div class="container">
    <!-- <h1>Вход</h1> -->

    <!-- <section> -->
        <!-- <div class="container"> -->
            <br>
            <div class="row">

                <div class="col-sm-8 col-sm-offset-4 padding-right">

                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li class="font-error"> <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="signup-form"><!--sign up form-->
                        <h2>Вход </h2>
                        <form action="" method="post">
                            <div class="mb-3">
                              <label for="email" class="form-label">Email</label>
                              <input type="email" class="form-control
                              <?php
                              if(isset($errors)) {
                                echo "border-error";
                              }
                              ?>
                              " id="email" name="email" placeholder="name@example.com" value="<?php echo $email; ?>">
                            </div>
                            <div class="mb-3">
                              <label for="password" class="form-label">Пароль</label>
                              <input type="password" class="form-control
                              <?php
                              if(isset($errors)) {
                                echo "border-error";
                              }
                              ?>
                              " id="password" name="password" placeholder="password" value="<?php echo $password; ?>">
                            </div>
                            <div class="mb-3">
                              <button class="btn btn-outline-primary" type="submit" name="submit" id="button-addon1">Войти</button>
                              <a href="registration"><button class="btn btn-outline-secondary" type="button" name="reg" id="button-addon2">Регистрация</button> </a>
                            </div>
                        </form>
                    </div><!--/sign up form-->
                    <br/>
                    <br/>
                </div>
            </div>
        <!-- </div> -->
    <!-- </section> -->
</div>

<?php
include 'layout/footer.php';
