<?php
include 'layout/header.php';
?>

<div class="container">
    <h1><?=$title ?></h1>
    <?php
    use App\Model\Post;
    use App\Model\MethodTypes;
    use App\Model\Users;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\ModelNotFoundException;

// App::error(function(ModelNotFoundException $e)
// {
//     return Response::make('Not Found', 404);
// });

    echo "<pre>";
    // print_r(Post::get());
    // print_r(MethodTypes::get());
    $methodTypes = MethodTypes::all();
    foreach ($methodTypes as $methodType) {  // Метод модели all получит все записи из связанной с моделью таблицы БД
        echo $methodType->id . ': ' . $methodType->name . '<br>'; // Ok!!!
    }

// $methodType_1 = MethodTypes::find(1);
// var_dump($methodType_1->name);
    echo "</pre>";
    ?>
</div>

<?php
include 'layout/footer.php';
