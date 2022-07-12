<?php

namespace App\Validator;

use App\Components\Helper;
use App\Model\Roles;
use App\Model\Users;

/**
 * Класс UserValidator - используется для валидации данных пользователя
 * @package App\Validator
 */
class UserValidator
{
    /**
     * Валидация данных авторизации
     * 
     * @param string $email - пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function loginValidate(string $email)
    {
        if (empty($email)) {
            $errors['no_email'] = 'Введите e-mail';
        } elseif (!Users::checkEmail($email)) {
            $errors['email'] = 'Некорректный email';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных отписки от рассылки
     * 
     * @param string $email - пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function unsubscribeValidate(string $email)
    {
        if (empty($email)) {
            $errors['email'] = 'Введите e-mail';
        } elseif (!Users::checkEmail($email)) {
            $errors['email'] = 'Некорректный email';
        } elseif (!Users::checkEmailExists($email)) {
            $errors['email'] = 'Вы не подписаны на рассылку.';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных подписки на рассылку
     * 
     * @param string $email - пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function subscribeValidate(string $email)
    {
        if (empty($email)) {
            $errors['no_email'] = '';
        } elseif (!Users::checkEmail($email)) {
            $errors['email'] = 'Некорректный email';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных регистрации пользователя
     * 
     * @param string $name - пользователя
     * @param string $email - пользователя
     * @param string $password - пользователя
     * @param string $confirm_password - подтверждение пароля
     * @param bool $rules - флаг об ознакомлении с правилами
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function registrationValidate(
        string $name,
        string $email,
        string $password,
        string $confirm_password,
        string $rules
    ) {

        if (!($name && $email && $password && $confirm_password && $rules)) {
            $errors['no_data'] = 'Не все поля заполнены';
        }

        if (!Users::checkName($name)) {
            $errors['checkName'] = ' - не должно быть короче 2-х символов';
        }

        if (!Users::checkEmail($email)) { //  Проверка правильности ввода e-mail
            $errors['checkEmail'] = ' - неправильный email';
        }

        if (!Users::checkPassword($password)) {
            $errors['checkPassword'] = ' - не должен быть короче 6-ти символов';
        }

        if (!Users::comparePasswords($password, $confirm_password)) {
            $errors['comparePasswords'] = ' - пароли не совпадают';
        }

        if (Users::checkEmailExists($email)) {
            $errors['checkEmailExists'] = ' - такой email уже используется';
        }

        if (Users::checkNameExists($name)) {
            $errors['checkNameExists'] = ' - такое имя уже используется';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных регистрации пользователя
     * 
     * @param string $name - пользователя
     * @param string $email - пользователя
     * @param string $aboutMe - пользователь о себе
     * @param Users $user - текущие данные пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function lkValidate(
        string $name,
        string $email,
        string $aboutMe,
        Users $user
    ) {
        if (!Users::checkName($name)) {
            $errors['name'] = '- имя не должно быть короче ' . MIN_NAME_LENGTH . '-х символов';
        } elseif (!($name == $user->name)) { // Если имя было изменено
            if (Users::checkNameExists($name)) {
                $errors['name'] = '- такое имя уже используется';
            }
        }

        if (!Users::checkEmail($email)) {
            $errors['email'] = '- неправильный email';
        } elseif (!($email == $user->email)) { // Если email был изменен
            if (Users::checkEmailExists($email)) {
                $errors['email'] = '- такой email уже используется';
            }
        }

        if (!Helper::checkLength(trim($aboutMe), MIN_ABOUTME_LENGTH, MAX_ABOUTME_LENGTH)) {
            $errors['aboutMe'] = ' - не должно быть более ' . MAX_ABOUTME_LENGTH . ' символов';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных для смены пароля пользователя
     * 
     * @param string $old_password - старый пароль
     * @param string $new_password - новый пароль
     * @param string $confirm_password - подтверждение пароля
     * @param Users $user - текущие данные пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function passwordValidate(
        string $oldPassword,
        string $newPassword,
        string $confirmPassword,
        Users $user
    ) {

        if (!($oldPassword && $newPassword && $confirmPassword)) {
            $errors[] = 'Не все поля заполнены';
        } elseif (!Users::checkUserData($user->email, $oldPassword)) {
            $errors['wrongPassword'] = 'Неправильный пароль.';
        } elseif (!Users::checkPassword($newPassword)) {
            $errors['checkPassword'] = 'Пароль не должен быть короче 6-ти символов';
        } elseif (!Users::comparePasswords($newPassword, $confirmPassword)) {
            $errors['comparePasswords'] = 'Пароли не совпадают';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных для управления ролями пользователей в админке
     * 
     * @param int $userId - id-пользователя
     * @param int $roleId - id-роли пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function adminUserValidate(
        int $userId,
        int $roleId
    ) {
        if (!$roleId) {
            $errors[] = 'Выберите роль пользователя';
        } elseif (!Users::getUserById($userId)) {
            $errors[] = 'Неверные данные пользователя. Обратитесь к администртору!';
        } elseif (!Roles::where('id', $roleId)->first()) {
            $errors['role'] = 'Неверные данные роли пользователя. Обратитесь к администртору!';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данных для управления ролями пользователей в админке
     * 
     * @param int $userId - id-пользователя
     * @param int $subscription - 1 - подписать, 0 - отписать от рассылки
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function adminUserSubscriptionValidate(
        int $userId,
        int $subscription
    ) {
        if (!Users::getUserById($userId)) {
            $errors[] = 'Неверные данные пользователя. Обратитесь к администртору!';
        } elseif (!in_array($subscription, [0, 1])) {
            $errors['subscription'] = 'Неверные данные по подписке пользователя. Обратитесь к администртору!';
        }

        return $errors ?? null;
    }
}
