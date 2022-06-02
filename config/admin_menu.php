<?php
// Данные для меню пользователя.
return [
   'admin-users' => [ // Ссылка без '/', а также Имя файла представления - admin-users.php
      'title' => 'Управление пользователями', // Название пункта меню
      'path' => 'admin-users', // Ссылка на страницу, куда ведет этот пункт меню
      'accessLevel' => ADMIN, // Уровень доступа
      'class' => UserController::class, // Класс контроллера
      'method' => 'adminUsers', // Метод в классе контроллера для обработки страницы 
      'sort' => 0, // Индекс сортировки (?)
   ],
   'admin-articles' => [ // Имя файла представления - admin-articles.php
      'title' => 'Управление статьями',
      'path' => 'admin-articles',
      'accessLevel' => CONTENT_MANAGER, // Уровень доступа
      'class' => ArticleController::class, // Класс контроллера
      'method' => 'adminArticles', // Метод в классе контроллера для обработки страницы 
      'sort' => 1,
   ],
   'admin-subscription' => [
      'title' => 'Управление подписками',
      'path' => 'admin-subscription',
      'accessLevel' => ADMIN, // Уровень доступа
      'class' => UserController::class, // Класс контроллера
      'method' => 'adminSubscription', // Метод в классе контроллера для обработки страницы 
      'sort' => 2,
   ],
   'admin-comments' => [
      'title' => 'Управление комментариями',
      'path' => 'admin-comments',
      'accessLevel' => CONTENT_MANAGER, // Уровень доступа
      'class' => ArticleController::class, // Класс контроллера
      'method' => 'adminComments', // Метод в классе контроллера для обработки страницы 
      'sort' => 3,
   ],
   'admin-cms' => [
      'title' => 'Управление статичными страницами',
      'path' => 'admin-cms',
      'accessLevel' => CONTENT_MANAGER, // Уровень доступа
      'class' => ArticleController::class, // Класс контроллера
      'method' => 'adminCMS', // Метод в классе контроллера для обработки страницы 
      'sort' => 4,
   ],
   'admin-settings' => [
      'title' => 'Дополнительные настройки',
      'path' => 'admin-settings',
      'accessLevel' => ADMIN, // Уровень доступа
      'class' => SiteController::class, // Класс контроллера
      'method' => 'additionalSettings', // Метод в классе контроллера для обработки страницы 
      'sort' => 5,
   ],
   'lk' => [
      'title' => 'Личный кабинет',
      'path' => 'lk',
      'accessLevel' => USER, // Уровень доступа
      'class' => UserController::class, // Класс контроллера
      'method' => 'lk', // Метод в классе контроллера для обработки страницы 
      'sort' => 6,
   ],
];
