<?php

namespace App\Services\Auto\Http\Requests;

use App\Interfaces\DTOGetterInterface;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для создания автомобиля
 *
 * @package App\Services\Auto\Requests
 */
class AutoRequest extends FormRequest implements DTOGetterInterface
{
    /**
     * Получить правила валидации, применяемые к запросу.
     *
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'year'          => [
                'required',
                'integer',
                'min:1900',
                "max:{$currentYear}"
            ],
            'mileage'       => [
                'required',
                'integer',
                'min:0',
                'max:9999999'
            ],
            'color'         => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z\s\-]+$/u'
            ],
            'auto_model_id' => [
                'required',
                'integer',
                'min:1',
                'exists:auto_models,id'
            ],
            'auto_mark_id'  => [
                'required',
                'integer',
                'min:1',
                'exists:auto_marks,id'
            ]
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
            'year.required'          => 'Год выпуска обязателен для заполнения.',
            'year.integer'           => 'Год выпуска должен быть числом.',
            'year.min'               => 'Год выпуска не может быть меньше 1900.',
            'year.max'               => 'Год выпуска не может быть больше текущего года.',

            'mileage.required'       => 'Пробег обязателен для заполнения.',
            'mileage.integer'        => 'Пробег должен быть числом.',
            'mileage.min'            => 'Пробег не может быть отрицательным.',
            'mileage.max'            => 'Пробег не может превышать 9,999,999 км.',

            'color.required'         => 'Цвет обязателен для заполнения.',
            'color.string'           => 'Цвет должен быть строкой.',
            'color.max'              => 'Цвет не должен превышать 50 символов.',
            'color.regex'            => 'Цвет может содержать только буквы, пробелы и дефисы.',

            'auto_model_id.required' => 'Модель автомобиля обязательна для заполнения.',
            'auto_model_id.integer'  => 'ID модели должен быть числом.',
            'auto_model_id.min'      => 'ID модели должен быть положительным числом.',
            'auto_model_id.exists'   => 'Выбранная модель автомобиля не существует.',

            'auto_mark_id.required'  => 'Марка автомобиля обязательна для заполнения.',
            'auto_mark_id.integer'   => 'ID марки должен быть числом.',
            'auto_mark_id.min'       => 'ID марки должен быть положительным числом.',
            'auto_mark_id.exists'    => 'Выбранная марка автомобиля не существует.',
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
            'year'          => 'год выпуска',
            'mileage'       => 'пробег',
            'color'         => 'цвет',
            'auto_model_id' => 'модель автомобиля',
            'auto_mark_id'  => 'марка автомобиля',
        ];
    }

    /**
     * Создать и вернуть DTO на основе валидированных данных.
     *
     * @return AutoRequestDTO
     */
    public function getDTO(): AutoRequestDTO
    {
        return AutoRequestDTO::from($this->validated());
    }
}
