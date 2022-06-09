<?php
// Шаблон errors/error.php, в коде которого подключены шапка и футер, а в теле выводится заголовок с текстом ошибки, переданной в этот шаблон.
include $_SERVER['DOCUMENT_ROOT'] . VIEW_DIR . 'layout/header.php';
?>

<div class="container">
    <h2><?= $title ?></h2>
    <p><?= $e ?></p>
    <a href="/"><?= $linkText ?></a>
</div>

<?php

include $_SERVER['DOCUMENT_ROOT'] . VIEW_DIR . 'layout/footer.php';
