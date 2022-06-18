<?php
include 'layout/admin_header.php';
include 'layout/admin_title.php';
?>

<div class="container">
  <div class="row">
    <div class="col-sm-12 col-sm-offset-4 padding-right">
      <div class="signup-form">
        <h2><?= $title ?></h2>
        <table class="table" width="100%">
          <thead>
            <tr>
              <th scope="col">Название</th>
              <th scope="col" width="400">Описание</th>
              <th scope="col">Значение</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($settings as $setting) : ?>
              <tr>
                <form action="#" method="POST">
                  <th scope="row"><?= $setting['name'] ?></th>
                  <td width="400"><?= $setting['description'] ?></td>
                  <td><input name="value" type="text" value="<?= $setting['value'] ?? '' ?>"></td>
                  <td><input name="id" hidden type="text" value="<?= $setting['id'] ?>"></td>
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
  </div>
</div>

<?php
include 'layout/admin_footer.php';
