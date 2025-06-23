<?php

namespace App\Services\Auto\Http\Requests;

use App\Interfaces\DTOGetterInterface;
use App\Services\Auto\DTO\Request\AutoModelRequestDTO;
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
     * Определяет, авторизован ли пользователь для выполнения этого запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для запроса.
     *
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Получить пользовательские сообщения об ошибках для валидации.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Название модели автомобиля обязательно для заполнения.',
            'name.string'   => 'Название модели автомобиля должно быть строкой.',
            'name.max'      => 'Название модели автомобиля не должно превышать 255 символов.',
        ];
    }

    /**
     * Получить пользовательские имена атрибутов для валидации.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'название модели автомобиля',
        ];
    }

    /**
     * @return AutoModelRequestDTO
     */
    public function getDTO(): AutoModelRequestDTO
    {
        return AutoModelRequestDTO::from($this->validated());
    }
}
