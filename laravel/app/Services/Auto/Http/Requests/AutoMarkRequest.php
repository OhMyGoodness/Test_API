<?php

declare(strict_types=1);

namespace App\Services\Auto\Http\Requests;

use App\Interfaces\DTOGetterInterface;
use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос на создание или обновление марки автомобиля.
 *
 * @package App\Services\Auto\Http\Requests
 *
 * @OA\Schema(
 *     schema="AutoMarkRequest",
 *     required={"name"},
 *     @OA\Property(property="name", ref="#/components/schemas/AutoMark/properties/name")
 * )
 */
class AutoMarkRequest extends FormRequest implements DTOGetterInterface
{
    /**
     * Правила валидации входящих данных.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Пользовательские сообщения об ошибках валидации.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Название марки автомобиля обязательно для заполнения.',
            'name.string'   => 'Название марки автомобиля должно быть строкой.',
            'name.max'      => 'Название марки автомобиля не должно превышать 255 символов.',
        ];
    }

    /**
     * Пользовательские имена атрибутов для валидации.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'название марки автомобиля',
        ];
    }

    /**
     * Создаёт и возвращает DTO на основе валидированных данных запроса.
     *
     * @return AutoMarkRequestDTO
     */
    public function getDTO(): AutoMarkRequestDTO
    {
        return AutoMarkRequestDTO::from($this->validated());
    }
}
