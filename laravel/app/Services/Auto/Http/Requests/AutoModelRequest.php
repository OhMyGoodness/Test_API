<?php

namespace App\Services\Auto\Http\Requests;

use App\Interfaces\DTOGetterInterface;
use App\Services\Auto\DTO\AutoModelDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="AutoModelRequest",
 *     required={"name"},
 *     @OA\Property(property="name", ref="#/components/schemas/AutoModel/properties/name"),
 * )
 *
 * @package App\Services\Auto\Http\Requests
 */
class AutoModelRequest extends FormRequest implements DTOGetterInterface
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }

    /**
     * @return AutoModelDTO
     */
    public function getDTO(): AutoModelDTO
    {
        return AutoModelDTO::from($this->validated());
    }
}
