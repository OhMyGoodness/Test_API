<?php

namespace App\Services\User;

use App\Services\User\DTO\UserLoginDTO;
use App\Services\User\Models\User;
use App\Services\User\Resources\UserLoginResource;

/**
 * @package App\Services\User
 */
class UserService
{
    /**
     * @param UserLoginDTO $dto
     * @return UserLoginResource
     */
    public function login(UserLoginDTO $dto): UserLoginResource
    {
        /** @var User $user */
        //$user = User::query()->firstOrFail();
        $user = User::byEmailAndPassword($dto->email, $dto->password)->firstOrFail();
        $token = $user->createToken($user->id);

        return new UserLoginResource($token->plainTextToken);
    }
}
