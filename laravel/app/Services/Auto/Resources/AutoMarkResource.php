<?php

namespace App\Services\Auto\Resources;

use App\Services\Auto\DTO\Response\AutoMarkResponseDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для преобразования марки автомобиля в JSON-ответ
 *
 * @property AutoMarkResponseDTO $resource
 *
 * @OA\Schema(
 *     schema="AutoMarkResource",
 *     title="AutoMarkResource",
 *     description="Ресурс марки автомобиля",
 *     @OA\Property(property="id", type="integer", example=1, description="Идентификатор марки"),
 *     @OA\Property(property="name", type="string", example="Toyota", description="Название марки")
 * )
 */
class AutoMarkResource extends JsonResource
{
    /**
     * Преобразует ресурс в массив для ответа API
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];
    }
}
