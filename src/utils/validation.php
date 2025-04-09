<?php

class Validation {
    /**
     * Проверяет, является ли строка корректным UUID
     *
     * @param string $uuid Проверяемая строка
     * @return bool Возвращает true если строка является UUID, иначе false
     */
    public static function isValidUUID(string $uuid): bool {
        return preg_match(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                $uuid
            ) === 1;
    }

    /**
     * Проверяет корректность логина или email
     *
     * @param string $input Входная строка (логин или email)
     * @return bool Возвращает true если строка соответствует формату, иначе false
     */
    public static function isValidLoginOrEmail(string $input): bool {
        // Проверка логина (допустимы буквы, цифры, нижние подчеркивания и точки, от 3 до 32 символов)
        if (preg_match('/^[a-zA-Z0-9_.]{3,32}$/', $input)) {
            return true;
        }

        // Проверка email
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Проверяет корректность email
     *
     * @param string $email Проверяемый email
     * @return bool Возвращает true если email корректен, иначе false
     */
    public static function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Проверяет корректность имени пользователя
     *
     * @param string $username Имя пользователя
     * @return bool Возвращает true если имя корректно, иначе false
     */
    public static function isValidUsername(string $username): bool {
        return preg_match('/^[a-zA-Z0-9_-]{3,32}$/', $username) === 1;
    }

    /**
     * Проверяет, является ли значение допустимым ID (положительное целое число)
     *
     * @param mixed $id Проверяемое значение
     * @return bool Возвращает true если значение является положительным целым числом
     */
    public static function isValidId($id): bool {
        return is_int($id) && $id > 0 ||
            (is_string($id) && ctype_digit($id) && $id > 0);
    }

    /**
     * Проверяет корректность пароля
     *
     * @param string $password Пароль для проверки
     * @param int $minLength Минимальная длина пароля (по умолчанию 8)
     * @param int $maxLength Максимальная длина пароля (по умолчанию 256)
     * @return bool Возвращает true если пароль соответствует требованиям
     */
    public static function isValidPassword(
        string $password,
        int $minLength = 8,
        int $maxLength = 256
    ): bool {
        $length = strlen($password);

        if ($length < $minLength || $length > $maxLength) {
            return false;
        }

        // Проверка на наличие хотя бы одной буквы, цифры и специального символа
        return preg_match('/[A-Za-z]/', $password) &&
            preg_match('/\d/', $password) &&
            preg_match('/[^A-Za-z0-9]/', $password);
    }

    /**
     * Проверяет корректность числового значения группы
     *
     * @param mixed $group Проверяемое значение
     * @return bool Возвращает true если значение является допустимым номером группы
     */
    public static function isValidGroup($group): bool {
        return is_int($group) && $group >= 0 ||
            (is_string($group) && ctype_digit($group) && $group >= 0);
    }

    /**
     * Проверяет, является ли значение корректной датой в формате YYYY-MM-DD
     *
     * @param string $date Проверяемая дата
     * @return bool Возвращает true если дата корректна
     */
    public static function isValidDate(string $date): bool {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Проверяет, является ли значение корректной меткой времени Unix
     *
     * @param mixed $timestamp Проверяемое значение
     * @return bool Возвращает true если значение является корректной меткой времени
     */
    public static function isValidTimestamp($timestamp): bool {
        if (!is_numeric($timestamp)) {
            return false;
        }

        $timestamp = (int)$timestamp;
        return $timestamp >= 0 && $timestamp <= PHP_INT_MAX;
    }

    /**
     * Проверяет, содержит ли стролько только допустимые символы
     *
     * @param string $input Проверяемая строка
     * @param string $pattern Регулярное выражение для проверки
     * @return bool Возвращает true если строка соответствует шаблону
     */
    public static function matchesPattern(string $input, string $pattern): bool {
        return preg_match($pattern, $input) === 1;
    }

    /**
     * Проверяет, есть ли необходимые ключи в массиве
     *
     * @param array $data Проверяемый массив
     * @param array $requiredKeys Массив обязательных ключей
     * @return bool Возвращает true если все ключи присутствуют
     */
    public static function hasRequiredKeys(array $data, array $requiredKeys): bool {
        return empty(array_diff($requiredKeys, array_keys($data)));
    }
}