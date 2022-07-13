<?php

namespace App\Validator;

use App\Components\Helper;
use App\Model\Methods;

/**
 * Класс ArticleValidator - используется для валидации данных статьи
 * @package App\Validator
 */
class ArticleValidator
{
    /**
     * Валидация данных статьи
     * 
     * @param string $articleTitle - название статьи
     * @param string $subtitle - подзаголовок
     * @param string $people - на сколько человек рассчитано
     * @param string $duration - длительность
     * @param string $description - краткое описание
     * @param string $author - автор
     * @param string $link - ссылка на страницу автора
     * @param string $articleMethods - типы методов, к которым принадлежит статья
     * @param string $content - подробное описание
     * 
     * @return array $errors - если валидация не прошла или null
     */
    public static function articleValidate(
        $articleTitle,
        $subtitle,
        $people,
        $duration,
        $description,
        $author,
        $link,
        $articleMethods,
        $content
    ) {
        if (!Helper::checkLength($articleTitle, MIN_TITLE_LENGTH, MAX_TITLE_LENGTH)) {
            $errors['articleTitle'] = 'Название статьи не должно быть меньше ' . MIN_TITLE_LENGTH . ' и не больше ' . MAX_TITLE_LENGTH . ' символов';
        }

        if ($subtitle && !Helper::checkLength($subtitle, 0, MAX_SUBTITLE_LENGTH)) {
            $errors['subtitle'] = 'Название подзаголовка не должно быть больше ' . MAX_SUBTITLE_LENGTH . ' символов';
        }

        if (!Helper::checkLength($people, MIN_PEOPLE_LENGTH, MAX_PEOPLE_LENGTH)) {
            $errors['people'] = 'Количество символов в поле не должно быть больше ' . MAX_PEOPLE_LENGTH;
        }

        if (!Helper::checkLength($duration, MIN_PEOPLE_LENGTH, MAX_DURATION_LENGTH)) {
            $errors['duration'] = 'Количество символов в поле не должно быть больше ' . MAX_DURATION_LENGTH;
        }

        if ($description && !Helper::checkLength($description, MIN_TITLE_LENGTH, MAX_DESCRIPTION_LENGTH)) {
            $errors['description'] = 'Должно быть не меньше ' . MIN_TITLE_LENGTH . ' и не больше ' . MAX_DESCRIPTION_LENGTH . ' символов';
        }

        if (!Helper::checkLength($author, MIN_TITLE_LENGTH, MAX_AUTHOR_LENGTH)) {
            $errors['author'] = 'Должно быть не меньше ' . MIN_TITLE_LENGTH . ' и не больше ' . MAX_AUTHOR_LENGTH . ' символов';
        }

        if ($link && !Helper::checkLength($link, 0, MAX_LINK_LENGTH)) {
            $errors['link'] = 'Количество символов в поле не должно быть больше ' . MAX_LINK_LENGTH;
        }

        if ($content && !Helper::checkLength($content, 0, MAX_CONTENT_LENGTH)) {
            $errors['content'] = 'Количество символов в поле не должно быть больше ' . MAX_CONTENT_LENGTH;
        }

        // Валидация типов методов
        $methodsObj = Methods::all();
        $methodsArr = [];

        foreach ($methodsObj as $method) {
            $methodsArr[] = $method->id;
        }

        if (is_array($articleMethods)) {
            foreach ($articleMethods as $method) {
                if (!in_array($method, $methodsArr)) {
                    $errors['method'] = 'Ошибка ввода типа метода. Обратитесь к Администратору.';
                }
            }
        }

        return $errors ?? null;
    }
}
