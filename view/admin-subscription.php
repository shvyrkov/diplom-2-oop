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
          <th scope="col">Имя</th>
          <th scope="col">e-mail</th>
          <th scope="col" class="text-center">Подписка</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $key => $user) : ?>
          <tr>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <form action="#" method="POST">
              <td class="text-center"><input name="subscription" class="form-check-input" type="checkbox" value="1" id="subscription" <?php if ($user['subscription']) : ?> checked <?php endif ?>></td>
              <td><input name="id" hidden type="text" value="<?= $user['id'] ?>"></td>
              <td><button type="submit" name="submit" class="btn btn-primary">Подтвердить</button></td>
            </form>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <br />
  <br />
</div>

<?php
include 'layout/admin_footer.php';
