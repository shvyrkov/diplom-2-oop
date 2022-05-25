<?php
include 'layout/header.php';
?>

<div class="container">
    <h1><?=$title ?></h1>
    <!-- <h1>Библиотека Фасилитатора — Главная</h1> -->
<!-- ---------------------TEST--------------------- -->
   <!-- <br>
    <a href="test/items/test2/100">Test page 1</a>
    <br>    
    <a href="test/items_3/test_3/444">Test page 2</a>
    <br>    
    <a href="test/qwerty/asdfg/115555">Test page 3</a>
    <br>
    <a href="test/qwerty/asdfg/115555?test=11">Test page 4</a>
    <br>
    <a href="posts/my_post_01/">posts test page</a>
    <br> -->

<!-- ---------------------TEST--------------------- -->
</div>

<div class="container-fluid my-4 mx-auto">
    <?php
    use App\Model\Articles;

echo "<pre>"; // -----------------------TEST--------------------------------------------

echo "</pre>"; // ----------------------TEST--------------------------------------------

    include 'layout/pagination.php';

    ?>
    
<div class="col-sm-4 " align="center">
    <pre>
    <?php
    print_r($_SESSION['user'] ?? ['Неавторизован']);
    ?>
    </pre>
</div>

        <?php if (!($_SESSION['user']['subscription'] ?? 0)) : // Если пользователь неподписан или неавторизован ?>
            <div class="row ">
                <div class="col-sm-4 " align="center">
                    <form action="subscription" method="POST">
                    <?php
                    if (!isset($_SESSION['user'])) : // Пользователь неавторизован - запрос e-mail?>
                        <label>Пользователь неавторизован</label>
                        <!-- <input  type="email" name="userEmail" value="" > -->
                    <? endif; ?>
                        <label>Пользователь неподписан</label>
                        <input  type="text" name="userId" value="<?=$_SESSION['user']['id'] ?? 0 ?>" >
                        <button type="submit" name="subscription" value="1" class="btn btn-primary">Подписаться на рассылку.</button>
                    </form>
                    <br>
                </div>
            </div>
        <? endif; ?>

    <div class="row">
        <?php
        foreach ($articles as $article) {
        ?>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="<?=ARTICLE . DIRECTORY_SEPARATOR . $article->id ?>">
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
                            <!-- <img id="MP1" src="img/mbt4.png" width="26" height="26"> -->
                        </div>
                        <h5 class="card-title MName"><?=$article->title ?></h5>
                        <h6 class="card-subtitle mb-2 VName"><?=$article->subtitle ?></h6>
                    </div>
                </div>
            </a>
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
</div><!--Container-fluid-->
<?php
include 'layout/footer.php';
