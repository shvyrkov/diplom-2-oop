<?php
// Шаблон errors/error.php, в коде которого подключены шапка и футер, а в теле выводится заголовок с текстом ошибки, переданной в этот шаблон.
include $_SERVER['DOCUMENT_ROOT'] . VIEW_DIR .'layout/header.php';
?>

<div class="container">
    <h2><?=$title ?></h2>
    <p><?=$e ?></p>
    <pre><?php
        // echo $traceAsString;
        // echo '<br>File: ' . $file;
        // echo '<br>Line: ' . $line;
    ?>
    </pre>
    <a href="/"><?=$linkText ?></a>
</div>

<?php
// echo "<pre>";
// echo "error.php - e: ";
// var_dump($e);
// echo "error.php _POST: <br>";
// var_dump($_POST);
// echo "<br>_GET:<br>";
// var_dump($_GET);
// echo "<br>SERVER:<br>";
// var_dump($_SERVER);
// echo "</pre>";

include $_SERVER['DOCUMENT_ROOT'] . VIEW_DIR . 'layout/footer.php';
