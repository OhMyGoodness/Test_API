<?php

namespace App\Services\Auto\Interfaces;

use App\Services\Auto\DTO\AutoDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @package App\Services\Auto\Interfaces
 */
interface AutoServiceInterface
{
    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection;

    /**
     * @param int $userId
     * @return ResourceCollection
     */
    public function listByUserId(int $userId): ResourceCollection;

    /**
     * @param AutoDTO $data
     * @return JsonResource
     */
    public function create(AutoDTO $data): JsonResource;

    /**
     * @param int $id
     * @param AutoDTO $data
     * @return JsonResource
     */
    public function update(int $id, AutoDTO $data): JsonResource;

    public function destroy(int $id): void;
}
