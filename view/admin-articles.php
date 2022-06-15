<?php

use App\Model\Articles;

include 'layout/admin_header.php';
include 'layout/admin_title.php';
include 'layout/admin-pagination.php';
?>

<div class="container-fluid my-4 mx-auto">
    <div class="row">
        <?php
        foreach ($articles as $article) :
        ?>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0">
                    <img src="<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article->thumbnail; ?>" class="card-img-top" alt="/<?php echo DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $article->thumbnail ?>">
                    <div class="card-body ">
                        <div class="Markers">
                            <?php
                            foreach (Articles::getMethods($article->id) as $method) : ?>
                                <img src="<?= DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR . $method->image; ?>">
                            <?php endforeach ?>
                        </div>
                        <h5 class="card-title MName"><?= $article->title ?></h5>
                        <h6 class="card-subtitle mb-2 VName"><?= $article->subtitle ?></h6>
                        <a href="/admin-cms/<?= $article->id ?>">
                            <button type="submit" name="submit" class="btn btn-secondary">Редактировать</button>
                        </a>
                    </div>
                </div>
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
include 'layout/admin_footer.php';
