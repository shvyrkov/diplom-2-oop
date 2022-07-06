<?php

namespace App\Validator;

use App\Model\Users;

/**
 * Класс UserValidator - контроллер валидации данных пользователя
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
            $errors['no_email'] = 'Введите e-mail';
        } elseif (!Users::checkEmail($email)) {
            $errors['email'] = 'Некорректный email';
        } elseif (!Users::checkEmailExists($email)) {
            $errors['emailExists'] = 'Вы не подписаны на рассылку.';
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
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function lkValidate(
        string $name,
        string $email,
        string $aboutMe
    ) {

        if (!($name && $email)) {
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
}
