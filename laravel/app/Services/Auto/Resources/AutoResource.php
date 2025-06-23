<?php

namespace App\Services\Auto\Resources;

use App\Services\Auto\DTO\Response\AutoResponseDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для преобразования автомобиля в JSON-ответ
 *
 * @property AutoResponseDTO $resource
 *
 * @OA\Schema(
 *     schema="AutoResource",
 *     title="AutoResource",
 *     description="Ресурс автомобиля",
 *     @OA\Property(property="id", type="integer", example=1, description="Идентификатор автомобиля"),
 *     @OA\Property(property="year", type="integer", example=2020, description="Год выпуска"),
 *     @OA\Property(property="mileage", type="integer", example=50000, description="Пробег в километрах"),
 *     @OA\Property(property="color", type="string", example="Чёрный", description="Цвет автомобиля"),
 *     @OA\Property(property="model", ref="#/components/schemas/AutoModelResource", description="Модель автомобиля"),
 *     @OA\Property(property="mark", ref="#/components/schemas/AutoMarkResource", description="Марка автомобиля"),
 * )
 */
class AutoResource extends JsonResource
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
            'id'            => $this->resource->id,
            'year'          => $this->resource->year,
            'mileage'       => $this->resource->mileage,
            'color'         => $this->resource->color,
            'model'         => $this->when($this->resource->model !== null, function () {
                return new AutoModelResource($this->resource->model);
            }),
            'mark'          => $this->when($this->resource->mark !== null, function () {
                return new AutoMarkResource($this->resource->mark);
            }),
        ];
    }
}
