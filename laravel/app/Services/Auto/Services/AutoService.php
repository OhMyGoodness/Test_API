<?php

namespace App\Services\Auto\Services;

use App\Services\Auto\DTO\AutoDTO;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\Auto\Models\Auto;
use App\Services\Auto\Resources\AutoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @package App\Services\Auto
 */
class AutoService implements AutoServiceInterface
{
    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return AutoResource::collection(
            Auto::query()->with(['model', 'mark'])->paginate()
        );
    }

    /**
     * @param int $userId
     * @return ResourceCollection
     */
    public function listByUserId(int $userId): ResourceCollection
    {
        return AutoResource::collection(Auto::byUserId($userId)->paginate());
    }

    /**
     * @param AutoDTO $data
     * @return JsonResource
     */
    public function create(AutoDTO $data): JsonResource
    {
        return new AutoResource(
            Auto::query()
                ->firstOrCreate($data->toArray())
        );
    }

    /**
     * @param int $id
     * @param AutoDTO $data
     * @return JsonResource
     */
    public function update(int $id, AutoDTO $data): JsonResource
    {
        $auto = Auto::byId($id)->first();
        $auto->update($data->toArray());
        $auto->refresh();

        return new AutoResource(
            $auto
        );
    }

    /**
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        Auto::destroy($id);
    }
}
