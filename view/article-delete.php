<?php
include 'layout/admin_header.php';
?>

<div class="container">
    <br>
    <div class="row">

        <div class="col-sm-8 col-sm-offset-4 padding-right">

            <?php
            if (isset($errors) && is_array($errors)) : ?>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li class="font-error"> <?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="signup-form">
                <?php
                if ($success) { ?>
                    <h3 class='font-success'>Статья была успешно удалена.</h3>
                <?php } else { ?>
                    <h3 class='font-fail'>Статья не была удалена.<br>Обратитесь к Администратору.</h3>
                <?php } ?>
            </div>
            <br />
            <br />
        </div>
    </div>
</div>

<?php
include 'layout/admin_footer.php';
