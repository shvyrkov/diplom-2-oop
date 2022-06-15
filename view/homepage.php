<?php

use App\Model\Articles;

include 'layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>
</div>

<div class="container-fluid my-4 mx-auto">
    <?php
    include 'layout/pagination.php';

    if (!($_SESSION['user']['subscription'] ?? 0)) : // Если пользователь неподписан или неавторизован 
    ?>
        <div class="row ">
            <div class="col-sm-4 " align="center">
                <form action="subscription" method="POST">
                    <button type="submit" name="subscribeAuthUser" value="1" class="btn btn-primary">Подписаться на рассылку.</button>
                </form>
                <br>
            </div>
        </div>
    <?php endif ?>

    <div class="row">
        <?php
        foreach ($articles as $article) :
        ?>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?= ARTICLE . DIRECTORY_SEPARATOR . $article->id ?>">
                    <div class="card border-0">
                        <img src="<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article->thumbnail; ?>" class="card-img-top" alt="/<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article->thumbnail ?>">
                        <div class="card-body ">
                            <div class="Markers">
                                <?php
                                foreach (Articles::getMethods($article->id) as $method) { ?>
                                    <img src="<?= DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $method->image; ?>">
                                <?php } ?>
                            </div>
                            <h5 class="card-title MName"><?= $article->title ?></h5>
                            <h6 class="card-subtitle mb-2 VName"><?= $article->subtitle ?></h6>
                        </div>
                    </div>
                </a>
                <div class="GBlock">
                    <div class="People"><?= $article->people ?></div>
                    <div class="Hours">><?= $article->duration ?></div>
                </div>
                <div class="IBlock"><?= $article->description ?></div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<?php
include 'layout/footer.php';
