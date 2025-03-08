<?php

namespace App\Services\Auto\Resources;

use App\Services\Auto\Models\Auto;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(schema="AutoResource",
 *     @OA\Property(
 *         property="data",
 *         @OA\Property(property="id", ref="#/components/schemas/Auto/properties/id"),
 *         @OA\Property(property="year", ref="#/components/schemas/Auto/properties/year"),
 *         @OA\Property(property="mileage", ref="#/components/schemas/Auto/properties/mileage"),
 *         @OA\Property(property="color", ref="#/components/schemas/Auto/properties/color"),
 *         @OA\Property(property="model", ref="#/components/schemas/AutoModel"),
 *         @OA\Property(property="mark", ref="#/components/schemas/AutoMark"),
 *         @OA\Property(property="created_at", ref="#/components/schemas/Auto/properties/created_at"),
 *         @OA\Property(property="updated_at", ref="#/components/schemas/Auto/properties/updated_at"),
 *     )
 * )
 *
 * @package App\Services\Auto\Resources
 */
class AutoResource extends JsonResource
{
    /** @var Auto */
    public $resource;

    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->resource->id,
            'year'       => $this->resource->year,
            'mileage'    => $this->resource->mileage,
            'color'      => $this->resource->color,
            'model'      => new AutoModelResource($this->resource->model),
            'mark'       => new AutoMarkResource($this->resource->mark),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
