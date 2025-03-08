<?php

namespace App\Services\User\Resources;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(schema="UserLoginResource",
 *     @OA\Property(property="token", type="string", description="Access token", example="123-456-789")
 * )
 *
 * @package App\Services\User\Resources\Responses
 */
class UserLoginResource extends JsonResponse
{
    /**
     * @param string $id
     */
    public function __construct(public string $id)
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'token' => $this->id
        ];
    }
}
