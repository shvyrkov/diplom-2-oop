<?php

namespace App\Validator;

use App\Components\Helper;

/**
 * Класс ArticleValidator - контроллер для валидации данных на странице статьи
 * @package App\Validator
 */
class ArticleValidator
{
    /**
     * Валидация текста комментария
     * 
     * @param string $text текст комментария
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function validate(string $text)
    {
        if (empty($text)) {
            $errors['no_text'] = 'Внесите комментарий';
        } elseif (!Helper::checkLength($text, MIN_COMMENT_LENGTH, MAX_COMMENT_LENGTH)) {
            $errors['text'] = 'Длина комментария не должна быть меньше ' . MIN_COMMENT_LENGTH . ' и больше ' . MAX_COMMENT_LENGTH . ' символов';
        }

        return $errors ?? null;
    }
}
