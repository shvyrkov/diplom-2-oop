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

            <?php
            foreach ($menu as $item) {
                if ($item['accessLevel'] < $_SESSION['user']['role']) { // Если уровень доступа не разрешен 
                    continue; // То пункт меню не выводится
                }

                if ($item['path'] == LK) { // Если это личный кабинет
                    continue; // То пункт меню не выводится
                }
            ?>
                <div>
                    <a class="btn btn-outline-primary" href="<?=$item['path'] ?>" role="button"><?=$item['title'] ?></a>
                </div>
            <?php
            }
            ?>
        </div>
    </div><!-- row -->
</div>

<?php
include 'layout/admin_footer.php';
