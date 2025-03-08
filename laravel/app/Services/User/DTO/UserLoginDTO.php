<?php

namespace App\Services\User\DTO;

use Spatie\LaravelData\Data;

/**
 * @package App\Services\User\DTO
 */
class UserLoginDTO extends Data
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
