<?php

namespace App\Services\Auto\Http\Requests;

use App\Interfaces\IDTOGetter;
use App\Services\Auto\DTO\AutoDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="AutoRequest",
 *     required={"year", "mileage", "color", "model_id", "mark_id"},
 *     @OA\Property(property="year", ref="#/components/schemas/Auto/properties/year"),
 *     @OA\Property(property="mileage", ref="#/components/schemas/Auto/properties/mileage"),
 *     @OA\Property(property="color", ref="#/components/schemas/Auto/properties/color"),
 *     @OA\Property(property="model_id", ref="#/components/schemas/Auto/properties/auto_model_id"),
 *     @OA\Property(property="mark_id", ref="#/components/schemas/Auto/properties/auto_mark_id"),
 *     @OA\Property(property="user_id", ref="#/components/schemas/Auto/properties/user_id"),
 * )
 *
 * @package App\Services\Auto\Http\Requests
 */
class AutoRequest extends FormRequest implements IDTOGetter
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'year'          => 'required|integer|min:1900|max:3000',
            'mileage'       => 'required|integer',
            'color'         => 'required|string',
            'auto_model_id' => 'required|integer|exists:auto_models,id',
            'auto_mark_id'  => 'required|integer|exists:auto_marks,id',
            'user_id'       => 'integer|nullable|exists:users,id',
        ];
    }

    /**
     * @return AutoDTO
     */
    public function getDTO(): AutoDTO
    {
        return AutoDTO::from($this->validated());
    }
}
