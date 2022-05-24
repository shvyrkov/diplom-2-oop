<?php
include 'layout/admin_header.php';
include 'layout/admin_title.php';
include 'layout/admin-pagination.php'; 
?>

<div class="container">
    <br>
    <div class="row">
        <div class="col-sm-12 col-sm-offset-4 padding-right">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Имя</th>
                <th scope="col">e-mail</th>
                <th scope="col">Текущая роль</th>
                <th scope="col" class="text-center">Admin</th>
                <th scope="col" class="text-center">Content-manager</th>
                <th scope="col" class="text-center">User</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($users as $user) {
              ?>
              <tr>
                <td><?=$user['name'] ?></td>
                <td><?=$user['email'] ?></td>
                <td><?php
                      foreach ($roles as $role) {
                          if ($user['role'] == $role['id']) {
                              echo $role['name'];
                          }
                      }
                ?></td>
                  <form action="" method="POST">
                      <td class="text-center"><input name="role" type="radio" value="<?=ADMIN ?>"></td>
                      <td class="text-center"><input name="role" type="radio" value="<?=CONTENT_MANAGER ?>"></td>
                      <td class="text-center"><input name="role" type="radio" value="<?=USER ?>"></td>
                      <td><input name="userId" hidden type="text" value="<?=$user['id'] ?>"></td>
                      <td><button type="submit" name="submit" class="btn btn-primary">Подтвердить</button></td>
                  </form>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          <br/>
          <br/>
        </div>
    </div><!-- row -->
</div><!-- container -->
<?php
include 'layout/admin_footer.php';
