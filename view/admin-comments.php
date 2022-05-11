<?php
include 'layout/admin_header.php';
?>

<div class="container">
  <br>
  <div class="row">
    <div class="col-sm-12 col-sm-offset-4 padding-right">
      <?php if (isset($errors) && is_array($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li class="font-error"> <?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="signup-form"><!--sign up form-->
        <h2><?=$title ?></h2>
        <table class="table">
          <thead>
            <tr>
              <!-- <th scope="col">#</th> -->
              <th scope="col">Текст комментария</th>
              <th scope="col">Дата</th>
              <th scope="col" class="text-center">Одобрен</th>
              <th scope="col" class="text-center">Отклонен</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($comments as $comment) {
                ?>
            <tr>
              <!-- <th scope="row">1</th> -->
              <td><?=$comment['text'] ?></td>
              <td><?=$comment['date'] ?></td>
              <form action="" method="POST">
                <td class="text-center"><input name="approve" class="form-check-input" type="checkbox" value="1" id="approve" 
                <?php
                if ($comment['approve']) {
                ?> checked
                <?php } ?>></td>
                <td class="text-center"><input name="deny" class="form-check-input" type="checkbox" value="1" id="deny" 
                <?php
                if ($comment['deny']) {
                ?> checked
                <?php } ?>></td>
                <td><input name="id" hidden type="text" value="<?=$comment['id'] ?>"></td>
                <td><button type="submit" name="submit" class="btn btn-primary">Подтвердить</button></td>
              </form>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div><!--/sign up form-->
      <br/>
      <br/>
    </div>
  </div>
</div>

<?php
include 'layout/admin_footer.php';
