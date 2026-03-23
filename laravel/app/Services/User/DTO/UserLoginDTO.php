<?php

declare(strict_types=1);

namespace App\Services\User\DTO;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных авторизации пользователя.
 *
 * @package App\Services\User\DTO
 */
final class UserLoginDTO extends Data
{
    /**
     * @param string $email Электронная почта пользователя
     * @param string $password Пароль пользователя
     */
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
