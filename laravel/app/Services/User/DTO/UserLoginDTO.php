<?php

namespace App\Services\User\DTO;

use Spatie\LaravelData\Data;

/**
 * DTO для данных авторизации
 *
 * @package App\Services\DTO
 */
class UserLoginDTO extends Data
{
    public function __construct(
        public string $email,
        public string $password
    )
    {
    }
}
