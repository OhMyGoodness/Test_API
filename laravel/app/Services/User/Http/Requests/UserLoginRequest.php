<?php

namespace App\Services\User\Http\Requests;

use App\Interfaces\IDTOGetter;
use App\Services\User\DTO\UserLoginDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(schema="UserLoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", description="E-Mail", example="test@test.com"),
 *     @OA\Property(property="password", type="string", description="User password", example="123456")
 * )
 *
 * @package App\Services\User\Http\Requests
 */
class UserLoginRequest extends FormRequest implements IDTOGetter
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * @return UserLoginDTO
     */
    public function getDTO(): UserLoginDTO
    {
        return UserLoginDTO::from($this->validated());
    }
}
