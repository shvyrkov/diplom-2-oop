<?php
include 'layout/admin_header.php';
?>

<div class="container">
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
</div><!-- container -->


<div class="container-fluid my-4 mx-auto">
    <?php
    use App\Model\Articles;

    // include 'layout/pagination.php';
    ?>
    <div class="row">
        <?php
        foreach ($articles as $article) {
        ?>
        <div class="col-12 col-sm-6 col-md-3">
            <!-- <a href="<?=ARTICLE . DIRECTORY_SEPARATOR . $article->id ?>"> -->
                <div class="card border-0">
                    <img src="<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article->thumbnail; ?>" class="card-img-top" alt="/<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article->thumbnail ?>">
                    <div class="card-body ">
                        <div class="Markers">
                            <?php
                            foreach (Articles::getMethods($article->id) as $method) { ?>
                                <img  src="<?=DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $method->image; ?>">
                            <?php
                            }
                            ?>
                        </div>
                        <h5 class="card-title MName"><?=$article->title ?></h5>
                        <h6 class="card-subtitle mb-2 VName"><?=$article->subtitle ?></h6>
                        <a href="/admin-cms/<?=$article->id ?>">
                            <button type="submit" name="submit" class="btn btn-secondary">Редактировать</button>
                        </a>
                    </div>
                </div>
            <!-- </a> -->
            <div class="GBlock">
                <div class="People"><?=$article->people ?></div>
                <div class="Hours">><?=$article->duration ?></div>
            </div>
            <div class="IBlock"><?=$article->description ?></div>
            
        </div>
        <?php
        }
        ?>
    </div><!-- row -->
</div><!-- container -->

<?php
include 'layout/admin_footer.php';
