<?php

namespace App\Services\Auto\DTO;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

/**
 * @package App\Services\Auto\DTO
 */
class AutoDTO extends Data
{
    /**
     * @param int $year
     * @param int $mileage
     * @param string $color
     * @param int $modelId
     * @param int $markId
     * @param int|null $userId
     */
    public function __construct(
        public int $year,
        public int $mileage,
        public string $color,
        #[MapName('auto_model_id')]
        public int $modelId,
        #[MapName('auto_mark_id')]
        public int $markId,
        #[MapName('user_id')]
        public int|null $userId
    ) {
    }
}
