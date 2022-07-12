<?php

namespace App\Validator;

use App\Components\Helper;
use App\Model\Comments;

/**
 * Класс CommentValidator - используется для валидации данных комментариев
 * @package App\Validator
 */
class CommentValidator
{
    /**
     * Валидация текста комментария
     * 
     * @param string $text текст комментария
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function textValidate(string $text)
    {
        if (empty($text)) {
            $errors['no_text'] = 'Внесите комментарий';
        } elseif (!Helper::checkLength($text, MIN_COMMENT_LENGTH, MAX_COMMENT_LENGTH)) {
            $errors['text'] = 'Длина комментария не должна быть меньше ' . MIN_COMMENT_LENGTH . ' и больше ' . MAX_COMMENT_LENGTH . ' символов';
        }

        return $errors ?? null;
    }

    /**
     * Валидация данныхь для одобрения комментария
     * 
     * @param int $id - комментария
     * @param string $approve одобрение комментария
     * @param string $deny отклонение комментария
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function approveValidate(int $id, string $approve, string $deny)
    {
        if (!Comments::where('id', $id)->first()) {
            $errors[] = 'Неверные данные комментария. Обратитесь к администртору!';
        } elseif (!in_array($approve, [0, 1])) {
            $errors[] = 'Неверные данные по одобрению комментария. Обратитесь к администртору!';
        } elseif (!in_array($deny, [0, 1])) {
            $errors[] = 'Неверные данные по отклонению комментария. Обратитесь к администртору!';
        }

        return $errors ?? null;
    }
}
