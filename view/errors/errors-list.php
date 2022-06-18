<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-4 padding-right">
      <?php if (isset($errors) && is_array($errors)) : ?>
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li class="font-error"> <?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>