<?php
namespace App\Components;
/**
 * Класс User - модель для работы с пользователями
 */
class User
{
    /**
     * Проверяет правильность ввода email
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
     * //@return mixed : integer user id or false
     * @return mixed : array user id or false
     */
    public static function checkUserData($email, $password)
    {
        // Соединение с БД

        
        // $password = md5($password);
        
        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';
        
        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Если запись существует, возвращаем id пользователя
            //return $user['id'];
            return $user;
        }
        return false;
    }

//------------- @TODO: ----------------------------------
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
        // Соединение с БД
        $db = Db::getConnection();

        $password = md5($password);

        // Текст запроса к БД
        $sql = 'INSERT INTO user (name, email, password) '
                . 'VALUES (:name, :email, :password)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Редактирование данных пользователя
     * 
     * @param integer $id <p>id пользователя</p>
     * @param string $name <p>Имя</p>
     * @param string $password <p>Пароль</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function edit($id, $name, $email)
    {
        // Соединение с БД
        $db = Db::getConnection();

// @TODO: проверка e-mail на наличие в БД!!!

        // Текст запроса к БД
        $sql = "UPDATE user 
            SET name = :name, email = :email
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        return $result->execute();
    }
    
    /**
     * Изменение пароля пользователя
     * 
     * @param integer $id <p>id пользователя</p>
     * @param string $password <p>Пароль пользователя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function editPassword($id, $password)
    {
        // Соединение с БД
        $db = Db::getConnection();
        
        $password = md5($password);

        // Текст запроса к БД
        $sql = "UPDATE user 
            SET password = :password
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }
    
    /**
     * Удаление аккаунта пользователя
     * 
     * @param integer $id <p>id пользователя</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function deleteUser($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "DELETE FROM user 
                WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }
    

    /**
     * Запоминаем пользователя в переменной сессии
     * 
     * @param integer $userId <p>id пользователя</p>
     */
    public static function auth($user)
    {
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/> 
     * Иначе перенаправляет на страницу входа
     * 
     * @return integer <p>Идентификатор пользователя</p>
     */
    public static function checkLogged()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        header("Location: /user/login");
    }

    /**
     * Проверяет является ли пользователь гостем
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
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
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет телефон: не меньше, чем 10 символов
     * 
     * @param string $phone <p>Телефон</p>
     * 
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
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
        if (strlen($password) >= 6) {
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
        // Соединение с БД        
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * Возвращает пользователя с указанным id
     * 
     * @param integer $id <p>id пользователя</p>
     * 
     * @return array <p>Массив с информацией о пользователе</p>
     */
    public static function getUserById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
    
    /**
     * Подтвержденипе e-mail пользователя: 
     *  - генрируется криптографически безопасный код;
     *  - отсылается пользователю на указанный e-mail;
     *  - возвращает данный код.
     * 
     * @param string $email <p>email пользователя</p>
     * 
     * @return int <p>код, высланный на e-mail пользователя</p>
     */
    public static function validateUserEmail($email)
    {
        // Генерация криптографически безопасного кода для проверки e-mail
        $code_sent = random_int ( 10000 , 99999 ); 
        // Отсылка пользователю E-mail с проверочным кодом (после всех проверок регистрационных данных).
        $subject = 'Проверка E-mail'; // Тема письма
        $message = 'Введите код: '.$code_sent; //Содержание письма
        // Отправка письма
        $result = mail($email, $subject, $message); // Возвращает TRUE, если письмо было принято для передачи, иначе FALSE.
        
        return $code_sent;
    }

}
