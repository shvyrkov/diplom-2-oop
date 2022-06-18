<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-4 padding-right">
            <div class="signup-form">
                <h2><?= $title ?></h2>
                <?php
use App\Components\Menu;
var_dump($title);
var_dump((Menu::getAdminMenu()));
var_dump( Menu::showTitle(Menu::getAdminMenu()));
                ?>
                <h2> $title </h2>
            </div>
        </div>
    </div>
</div>