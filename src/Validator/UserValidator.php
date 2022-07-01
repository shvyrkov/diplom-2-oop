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
     * Валидация данных подписки на рассылку
     * 
     * @param string $email - пользователя
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function subscribeValidate(string $email)
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
}
