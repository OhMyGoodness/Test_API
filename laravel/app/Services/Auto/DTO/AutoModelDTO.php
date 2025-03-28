<?php

namespace App\Services\Auto\DTO;

use Spatie\LaravelData\Data;

/**
 * @package App\Services\Auto\DTO
 */
class AutoModelDTO extends Data
{
    /**
     * @param string $name
     */
    public function __construct(public string $name)
    {
    }
}
