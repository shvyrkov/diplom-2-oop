<?php
// Данные для меню пользователя.
return [
   'main' => [
      'title' => 'Библиотека фасилитатора', // Название пункта меню
      'path' => '/', // Ссылка на страницу, куда ведет этот пункт меню
      'class' => SiteController::class, // Класс модели
      'method' => 'index', // Метод в классе модели для обработки страницы
      'sort' => 0, // Индекс сортировки (?)
   ],
   'about' => [
      'title' => 'О нас',
      'path' => '/about',
      'class' => SiteController::class, // Класс модели
      'method' => 'about', // Метод в классе модели для обработки страницы
      'sort' => 1,
   ],
   'contacts' => [
      'title' => 'Контакты',
      'path' => '/contacts',
      'class' => SiteController::class, // Класс модели
      'method' => 'contacts', // Метод в классе модели для обработки страницы
      'sort' => 2,
   ],
   'post' => [
      'title' => 'Рассылка',
      'path' => '/post',
      'class' => PostController::class, // Класс модели
      'method' => 'post', // Метод в классе модели для обработки страницы
      'sort' => 3,
   ],
   'lk' => [
      'title' => 'Личный кабинет',
      'path' => '/lk',
      'class' => UserController::class, // Класс модели
      'method' => 'lk', // Метод в классе модели для обработки страницы
      'sort' => 4,
   ],
];
