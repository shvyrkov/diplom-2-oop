<div class="container">
    <br>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-4 padding-right">
            <?php if (isset($errors) && is_array($errors)) : ?>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li class="font-error"> <?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="signup-form">
                <h2><?= $title ?></h2>
            </div>
            <br />
            <br />
        </div>
    </div>
</div>