<?php

namespace App\Services\Auto\Resources;

use App\Services\Auto\Models\AutoModel;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(schema="AutoModelResource",
 *     @OA\Property(property="id", ref="#/components/schemas/AutoModel/properties/id"),
 *     @OA\Property(property="name", ref="#/components/schemas/AutoModel/properties/name"),
 *     @OA\Property(property="created_at", ref="#/components/schemas/AutoModel/properties/created_at"),
 *     @OA\Property(property="updated_at", ref="#/components/schemas/AutoModel/properties/updated_at"),
 * )
 *
 * @package App\Services\Auto\Resources
 */
class AutoModelResource extends JsonResource
{
    /**
     * @var string
     */
    public $resource = AutoModel::class;

    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->resource->id,
            'name'       => $this->resource->name,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
