<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Класс для работы с таблицей 'users'
 */
class Users extends Model
{
    /**
     * Первичный ключ таблицы Users.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * Проверка правильности ввода email
     * 
     * @param string $email
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * 
     * @param string $email
     * @param string $password
     * 
     * @return mixed : array $user or false
     */
    public static function checkUserData($email, $password)
    {
        $user = Users::where('email', $email)
                ->get();

        if (isset($user[0])) {
            $passwordVerification = password_verify($password , $user[0]['password']);

            if ($passwordVerification) {

                 return $user[0];
             }
         }

        return false;
    }

    /**
     * Проверяет имя: не меньше, чем 2 символа
     * 
     * @param string $name <p>Имя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkName($name)
    {
        if (iconv_strlen($name) >= 2) {

            return true;
        }

        return false;
    }

    /**
     * Проверяет имя: не меньше, чем 6 символов
     * 
     * @param string $password <p>Пароль</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPassword($password)
    {
        if (iconv_strlen($password) >= 6) {

            return true;
        }

        return false;
    }

    /**
     * Проверяет правильность повторного ввода пароля при регистрации.
     * 
     * @param string $password_1 <p>Пароль введенный 1-й раз</p>
     * @param string $password_2 <p>Пароль введенный 2-й раз</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function comparePasswords($password_1, $password_2)
    {
        if ($password_1 == $password_2) {

            return true;
        }

        return false;
    }

    /**
     * Проверяет не занят ли email другим пользователем
     * 
     * @param string $email <p>E-mail</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmailExists($email)
    {
        $user = Users::where('email', '=', $email)
                ->get();

        if (isset($user[0])) {

            return true;
        }

        return false;
    }

    /**
     * Проверяет не занято ли Имя другим пользователем
     * 
     * @param string $name <p>Имя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkNameExists($name)
    {
        $user = Users::where('name', '=', $name)
                ->get();

        if (isset($user[0])) {

            return true;
        }

        return false;
    }

    /**
     * Регистрация пользователя 
     * 
     * @param string $name <p>Имя</p>
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function register($name, $email, $password)
    {
        Users::insert(
            ['name' => $name, 'email' => $email, 'password' => $password]
        );

        return true;
    }

    /**
     * Обновление данных пользователя 
     * 
     * @param string $id <p>id-пользователя в БД</p>
     * @param string $name <p>Имя</p>
     * @param string $email <p>E-mail</p>
     * @param string $aboutMe <p>О себе</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function updateUser($id, $name, $email, $aboutMe, $avatar)
    {
        Users::where('id', $id)
            ->update(['name' => $name, 'email' => $email, 'aboutMe' => $aboutMe, 'avatar' => $avatar]);

        return true;
    }

    /**
     * Получаем данные пользователя по $email - @TODELETE:?
     * 
     * @param string $email
     * 
     * @return mixed : array $user or false
     */
    public static function getUserByEmail($email)
    {
        $user = Users::where('email', '=', $email)
                ->get();

        if (isset($user[0])) {

             return $user[0];
         } 

        return false;
    }

    /**
     * Запоминаем пользователя в переменной сессии
     * 
     * @param array $user - массив данных пользователя
     */
    public static function auth($user)
    {
        // Записываем данные пользователя в сессию
        foreach ($user->attributes as $key => $value) {
            if ($key === 'password') { // кроме пароля
                continue;
            }

            $_SESSION['user'][$key] = $value;
        }
    }

    /**
     * Выход из аккаунта
     */
    public static function exit()
    {
        // unset($_SESSION); // Не работает, только если указать ключ
        session_unset(); // Уничтожаем данные сессии
        session_destroy(); // Уничтожаем сессию
    }

    /**
    * Функция перевода байтов в Mb, kB или b в зависимости от их количества
    *
    * @param int $bytes - количество байт
    * 
    * @return string $bytes - количество байт, переведенное в Mb, kB или b в зависимости от их количества
    */
    public static function formatSize($bytes) {

        if ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' Mb';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        } else {
            $bytes = $bytes . ' b';
        }

        return $bytes;
    }

    /**
    * Получение данных пользователя
    * 
    * @param string $id 
    * 
    * @return array $user - массив с данными пользователя
    */
    public static function getUserById($id = 1)
    {
        $user = Users::where('id', '=' , $id)
                ->get();

        if (isset($user[0])) {

             return $user[0];
        }

        return false;
    }

    /**
     * Изменение роли пользователя 
     * 
     * @param string $email <p>email пользователя в БД</p>
     * @param string $password <p>пароль</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changePassword($email, $password)
    {
        Users::where('email', $email)
            ->update(['password' => $password]);

        return true;
    }

    /**
     * Изменение роли пользователя 
     * 
     * @param string $id <p>id-пользователя в БД</p>
     * @param string $role <p>Роль пользователя (номер)</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changeRole($id, $role)
    {
        Users::where('id', $id)
            ->update(['role' => $role]);

        return true;
    }

    /**
     * Изменение подписки пользователя 
     * 
     * @param string $id <p>id-пользователя в БД</p>
     * @param string $subscription <p>Подписан ли пользователь на рассылку (0 - нет, 1 - да)</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changeSubscription($id, $subscription)
    {
        Users::where('id', $id)
            ->update(['subscription' => $subscription]);

        return true;
    }

    /**
    * Получение пользователей из БД
    * 
    * @param int $limit [optional] Количество пользователей на странице
    * @param int $page [optional] Номер страницы
    * 
    * @return array $articles - массив со пользователеми.
    */
    public static function getUsers($limit = 20, $page = 1)
    {
        $users = [];
        $offset = ($page - 1) * $limit;

        $users = Users::where('id', '>' , 0)
                ->orderBy('id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

        return $users;
    }
// --------------------- Test -----------------------------
    /**
    * Получение данных всех пользователей
    * 
    * @return array $users - массив с данными пользователей
    */
    public static function getAllUsers()
    {
        $users = [];
        $users = Users::where('id', '>' , 0)
            ->join('roles', 'users.role', '=', 'roles.id')
            // ->select('users.*', 'contacts.phone', 'orders.price')
            ->get();

        return $users;
    }

}
