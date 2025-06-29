<?php

namespace App\Services\Auto\Http\Requests;

use App\Interfaces\DTOGetterInterface;
use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="AutoMarkRequest",
 *     required={"name"},
 *     @OA\Property(property="name", ref="#/components/schemas/AutoMark/properties/name"),
 * )
 *
 * @package App\Services\Auto\Http\Requests
 */
class AutoMarkRequest extends FormRequest implements DTOGetterInterface
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }

    /**
     * @return AutoMarkRequestDTO
     */
    public function getDTO(): AutoMarkRequestDTO
    {
        return AutoMarkRequestDTO::from($this->validated());
    }
}
