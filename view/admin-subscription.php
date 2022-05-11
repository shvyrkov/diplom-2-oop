<?php
include 'layout/admin_header.php';
?>

<div class="container">
    <!-- <h1>Вход</h1> -->

    <!-- <section> -->
        <!-- <div class="container"> -->
            <br>
            <div class="row">

                <div class="col-sm-12 col-sm-offset-4 padding-right">

                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li class="font-error"> <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="signup-form"><!--sign up form-->
                        <h2><?=$title ?></h2>

                        <table class="table">
                          <thead>
                            <tr>
                              <!-- <th scope="col">#</th> -->
                              <th scope="col">Имя</th>
                              <th scope="col">e-mail</th>
                              <th scope="col" class="text-center">Подписка</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            foreach ($users as $key => $user) {
                            ?>
                            <tr>
                              <!-- <th scope="row">1</th> -->
                              <td><?=$user['name'] ?></td>
                              <td><?=$user['email'] ?></td>
                              <form action="#" method="POST">
                                <td class="text-center"><input name="subscription" class="form-check-input" type="checkbox" value="1" id="subscription" 
                                <?php
                                if ($user['subscription']) {
                                ?> checked
                                <?php } ?>></td>
                                <td><input name="id" hidden type="text" value="<?=$user['id'] ?>"></td>
                                <td><button type="submit" name="submit" class="btn btn-primary">Подтвердить</button></td>
                              </form>
                            </tr>
                        <?php } ?>

                          </tbody>
                        </table>
<pre>
<?php
// echo "<h4>----------TEST----------------</h4>";
?>
</pre>
                        
                    </div><!--/sign up form-->
                    <br/>
                    <br/>
                </div>
            </div>
        <!-- </div> -->
    <!-- </section> -->
</div>

<?php
include 'layout/admin_footer.php';
