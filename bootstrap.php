<?php
define('APP_DIR', DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR); // Константа APP_DIR указывает на директорию с классами.
define('VIEW_DIR', DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR); // Константа VIEW_DIR указывает на директорию с представлениями.
define('CONFIG_DIR', $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR); // Константа CONFIG_DIR указывает на директорию с конфигурацией проекта.
define('IMG', 'img'); // Константа IMG указывает на директорию с изображениями.
define('USER_MENU', 'user_menu.php'); // Константа USER_MENU указывает на файл с конфигурацией меню пользователя.
define('ADMIN_MENU', 'admin_menu.php'); // Константа ADMIN_MENU указывает на файл с конфигурацией меню администратора.
define('IMAGE_TYPES', 'image_file_types.php'); // Указывает на файл с допустимыми типами файлов изображений
define('PAGE_PATTERN', '~page-([0-9]+)~'); // Шаблон для получения номера страницы из строки запроса.

define('ADMIN_PAGE_PATTERN', '~\w/page-([0-9]+)~'); // Шаблон для получения номера страницы из строки запроса.
define('METHOD_PAGE_PATTERN', '~\w/page-([0-9]+)~'); // Шаблон для получения номера страницы из строки запроса.

define('ARTICLE', 'article'); // Константа для формирования строки запроса статьи вида '/article/id-статьи'.
define('UNSUBSCRIBE', DIRECTORY_SEPARATOR . $_SERVER["HTTP_HOST"] . DIRECTORY_SEPARATOR . 'unsubscribe'); // Константа для перехода на страницу отписки от рассылки.

define('FILE_SIZE', 2097152); // Максимальный размер файла для загрузки.
// define('FILE_TYPES', 2097152); // Максимальный размер файла для загрузки.
define('AVATAR_STORAGE', $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR); // Папка для хранения файла загрузки аватара.
define('AVATARS', DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR); // Папка для хранения файла загрузки.
define('DEFAULT_AVATAR', 'no-photo.jpg'); // Имя файла, если аватар не загружен

define('MIN_COMMENT_LENGTH', 2); // Минимальный размер комментария в символах.
define('MAX_COMMENT_LENGTH', 5000); // Максимальный размер комментария в символах.

define('ADMIN', 1); // Роль админа.
define('CONTENT_MANAGER', 2); // Роль контент-менеджера.
define('USER', 3); // Роль контент-менеджера.
define('NO_USER', 4); // Роль, если пользователь не авторизован.
define('LK', '/lk'); // Константа для ссылки на личный кабинет

define('MIN_NAME_LENGTH', 2); // Мин. кол-во символов в имени пользователя
define('MAX_NAME_LENGTH', 250); // Макс. кол-во символов в имени пользователя
define('MIN_PASSWORD_LENGTH', 6); // Мин. кол-во символов в пароле пользователя
define('MAX_PASSWORD_LENGTH', 20); // Макс. кол-во символов в пароле пользователя
define('MIN_ABOUTME_LENGTH', 0); // Мин. кол-во символов в поле "О себе" пользователя
define('MAX_ABOUTME_LENGTH', 5000); // Макс. кол-во символов в поле "О себе" пользователя

define('MIN_TITLE_LENGTH', 2); // Мин. кол-во символов в названии статьи
define('MAX_TITLE_LENGTH', 250); // Макс. кол-во символов в названии статьи
define('MAX_SUBTITLE_LENGTH', 250); // Макс. кол-во символов в подзаголовке статьи
define('MAX_DESCRIPTION_LENGTH', 1000); // Макс. кол-во символов в кратком описании статьи
define('MAX_CONTENT_LENGTH',  65535); // Макс. кол-во символов в содержании статьи
define('MIN_PEOPLE_LENGTH', 1); // Мин. кол-во символов в кол-ве человек 
define('MAX_PEOPLE_LENGTH', 50); // Макс. кол-во символов в кол-ве человек 
define('MAX_DURATION_LENGTH', 50); // Макс. кол-во символов в длительности 
define('MAX_AUTHOR_LENGTH', 150); // Макс. кол-во символов в имени автора статьи
define('MAX_LINK_LENGTH', 350); // Макс. кол-во символов в ссылке на автора статьи
define('DEFAULT_ARTICLE_IMAGE', 'cs-big.png'); // Имя файла, если изображение для статьи не загружено
define('IMG_STORAGE', $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . IMG . DIRECTORY_SEPARATOR); // Папка для хранения файла изображения

require_once __DIR__ . '/vendor/autoload.php';
