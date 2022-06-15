<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Users
 * @package App\Model
 */
class Users extends Model
{
    /**
     * Первичный ключ таблицы Users.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
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
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * 
     * @param string $email
     * @param string $password
     * 
     * @return object $user or null
     */
    public static function checkUserData($email, $password)
    {
        $user = Users::where('email', $email)
            ->first();

        if (isset($user)) {
            if (password_verify($password, $user['password'])) {

                return $user;
            }
        }

        return null;
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
        return mb_strlen($name, 'UTF-8') >= MIN_NAME_LENGTH;
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
        return mb_strlen($password, 'UTF-8') >= MIN_PASSWORD_LENGTH;
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
        return $password_1 == $password_2;
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
        $user = Users::where('email', $email)
            ->first();

        return $user ? true : false;
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
        $user = Users::where('name', $name)
            ->first();

        return $user ? true : false;
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
    public static function register($email, $role = null, $name = null, $password = null)
    {
        $id = Users::insertGetId(
            ['email' => $email, 'role' => $role, 'name' => $name, 'password' => $password]
        );

        return $id ? true : false;
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
        $result = Users::where('id', $id)
            ->update(['name' => $name, 'email' => $email, 'aboutMe' => $aboutMe, 'avatar' => $avatar]);

        return $result ? true : false;
    }

    /**
     * Получаем данные пользователя по $email
     * 
     * @param string $email
     * 
     * @return object $user or null
     */
    public static function getUserByEmail($email)
    {
        $user = Users::where('email', $email)
            ->first();

        return $user;
    }

    /**
     * Запоминаем пользователя в переменной сессии
     * 
     * @param object $user - объект данных пользователя
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
        session_unset(); // Уничтожаем данные сессии
        session_destroy(); // Уничтожаем сессию
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
        $user = Users::where('id', $id)
            ->first();

        return $user;
    }
    /**
     * Получение данных пользователя
     * 
     * @return array $users - массив с пользователями, подписанными на рассылку
     */
    public static function getSubscribedUsers()
    {
        $users = Users::where('subscription', 1)
            ->get();

        return $users;
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
        $result = Users::where('email', $email)
            ->update(['password' => $password]);

        return $result ? true : false;
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
        $result = Users::where('id', $id)
            ->update(['role' => $role]);

        return $result ? true : false;
    }

    /**
     * Изменение подписки пользователя 
     * 
     * @param string $id <p>id-пользователя в БД, null - если пользователя нет(?)</p>
     * @param string $subscription <p>Подписан ли пользователь на рассылку (0 - нет, 1 - да)</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function changeSubscription($id, $subscription)
    {
        $result = Users::where('id', $id)
            ->update(['subscription' => $subscription]);

        return $result ? true : false;
    }

    /**
     * Получение пользователей из БД
     * 
     * @param int $limit [optional] Количество пользователей на странице
     * @param int $page [optional] Номер страницы
     * 
     * @return object $users - объект с пользователеми.
     */
    public static function getUsers($limit = 20, $page = 1)
    {
        $offset = ($page - 1) * $limit;

        $users = Users::where('id', '>', 0)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $users;
    }
}
