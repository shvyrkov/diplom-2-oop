<?php

use App\Components\Menu;

$menu = Menu::getAdminMenu(); // Меню пользователя есть на всех страницах
$title = $title ?? ''; // Заголовок не всегда передается

include 'base/header.php';
?>
<title><?= $title ?></title>
</head>

<body>
  <!-- Меню пользователя -->
  <nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="admin">Админка</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php
          foreach ($menu as $item) :
            if ($item['accessLevel'] < $_SESSION['user']['role']) { // Если уровень доступа не разрешен 
              continue; // То пункт меню не выводится
            }
          ?>
            <li class="nav-item me-2">
              <a class="nav-link <?php
                                  if ($title === $item['title']) {
                                    echo "active";
                                  }
                                  ?> " href="<?= $item['path'] ?>"><?= $item['title'] ?></a>
            </li>
          <?php endforeach ?>
        </ul>
        <?php
        if (isset($_SESSION['user']['id'])) { // Если была регистрация или вход в л.к.-->
        ?>
          <a href="/exit"><button type="button" class="btn btn-outline-success my-2 my-sm-0" name="exit">Выход</button></a>
        <?php
        } else { // Если входа или регистрации не было
        ?>
          <a href="/login">
            <button class="btn btn-outline-success my-2 my-sm-0">Вход</button>
          </a>
          <a href="/registration">
            <button class="btn btn-outline-success my-2 my-sm-0">Регистрация</button>
          </a>
        <?php
        }
        ?>
      </div>
    </div>
  </nav>
  <!-- Заголовок - название сайта. -->
  <div class="container-fluid px-5-auto title">
    <div class="SiteName">Библиотека фасилитатора</div>
  </div>
  <br>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-6">
        <h2>Административная панель</h2>
      </div>
    </div>
  </div>