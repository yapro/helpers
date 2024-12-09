<?php

declare(strict_types=1);

namespace YaPro\Helper\Validation;

/**
 * Класс валидации email-адресов.
 */
class EmailValidator
{
    public const PATTERN = '(([^а-яА-Я<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([а-яА-Яa-zA-Z\-0-9]+\.)+[а-яА-Яa-zA-Z]{2,}))';

    public function isContainsEmail(string $string): bool
    {
        return preg_match('/' . self::PATTERN . '/su', $string) === 1;
    }

    /**
     * Валидация.
     *
     * @internal Если обнаружится, что данная функция работает неверно, стоит попробовать
     * filter_var($email, FILTER_VALIDATE_EMAIL) или {@see https://symfony.com/doc/current/reference/constraints/Email.html}
     *
     * @param string $email Адрес электронной почты для проверки
     *
     * @return bool
     */
    public function isValid($email): bool
    {
        if (is_string($email) === false || empty($email) === true) {
            return false;
        }
        //Макс длина в БД - 128
        if (mb_strlen($email) > 128) {
            return false;
        }

        if (preg_match('/^' . self::PATTERN . '$/', $email) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Возвращает провалидированный емэйл-адрес или выбрасывает эксепшен
     *
     * @param $email
     *
     * @return string
     *
     * @throws UnexpectedValueException
     */
    public function validateEmail($email): string
    {
        if ($this->isValid($email) === false) {
            throw new UnexpectedValueException('Email not valid');
        }

        return $email;
    }
}
