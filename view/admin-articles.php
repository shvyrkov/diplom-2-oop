<?php
include 'layout/admin_header.php';
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
                        <!-- <h2>Админка</h2> -->
                        <h2><?=$title ?></h2>
<pre>
<?php
// use App\Components\Menu;
// print_r($menu);
// print_r($_SERVER);

// echo "<br>";
// var_dump(Menu::showTitle(Menu::getAdminMenu()));
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
