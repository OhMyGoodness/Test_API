<?php

namespace App\Services\Auto\Resources;

use App\Services\Auto\DTO\Response\AutoModelResponseDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для преобразования модели автомобиля в JSON-ответ
 *
 * @property AutoModelResponseDTO $resource
 *
 * @OA\Schema(
 *     schema="AutoModelResource",
 *     title="AutoModelResource",
 *     description="Ресурс модели автомобиля",
 *     @OA\Property(property="id", type="integer", example=1, description="Идентификатор модели"),
 *     @OA\Property(property="name", type="string", example="Camry", description="Название модели"),
 * )
 */
class AutoModelResource extends JsonResource
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
