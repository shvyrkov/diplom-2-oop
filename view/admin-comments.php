<?php
include 'layout/admin_header.php';
include 'layout/admin_title.php';
include 'layout/admin-pagination.php';
?>

<div class="container">
  <br>
  <div class="row">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Текст комментария</th>
          <th scope="col">Дата</th>
          <th scope="col" class="text-center">Одобрен</th>
          <th scope="col" class="text-center">Отклонен</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($comments as $comment) :
        ?>
          <tr>
            <td><?= $comment['text'] ?></td>
            <td><?= $comment['date'] ?></td>
            <form action="" method="POST">
              <td class="text-center"><input name="approve" class="form-check-input" type="checkbox" value="1" id="approve" <?php if ($comment['approve']) : ?> checked <?php endif ?>></td>
              <td class="text-center"><input name="deny" class="form-check-input" type="checkbox" value="1" id="deny" <?php if ($comment['deny']) : ?> checked <?php endif ?>></td>
              <td><input name="id" hidden type="text" value="<?= $comment['id'] ?>"></td>
              <td><button type="submit" name="submit" class="btn btn-primary">Подтвердить</button></td>
            </form>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>

<?php
include 'layout/admin_footer.php';
