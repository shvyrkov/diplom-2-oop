<?php
use App\Model\Post;
use App\Model\Users;

include 'layout/header.php';
?>
<div class="container">
    <h1><?=$title ?></h1>
</div>
<?php
include 'layout/admin-pagination.php'; 
?>
<div class="container">
  <br>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">e-mail</th>
          <th scope="col" class="text-center">Заголовок письма</th>
          <th scope="col" class="text-center">Содержимое письма</th>
          <th scope="col" class="text-center">Дата рассылки</th>
          <th scope="col" class="text-center">Ссылка на страницу со статьей</th>
          <th scope="col" class="text-center">Ссылка для отписки от рассылки</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($mails as $key => $mail) {
        ?>
        <tr>
          <td><?=$mail['email'] ?></td>
          <td><?=$mail['subject'] ?></td>
          <td><?=$mail['message'] ?></td>
          <td><?=$mail['date'] ?></td>
          <td><a href="/<?=$mail['link'] ?>"><button type="submit" name="submit" class="btn btn-primary">На страницу статьи</button></a></td>
          <td><a href="/<?=$mail['unsubscribe'] ?>"><button type="submit" name="submit" class="btn btn-secondary">Отписаться от рассылки</button></a></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <br/>
  <br/>
</div>

<?php
include 'layout/footer.php';
