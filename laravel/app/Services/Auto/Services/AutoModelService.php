<?php

namespace App\Services\Auto\Services;

use App\Services\Auto\DTO\AutoModelDTO;
use App\Services\Auto\Models\AutoModel;
use App\Services\Auto\Resources\AutoModelResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @package App\Services\Auto\Services
 */
class AutoModelService
{
    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return AutoModelResource::collection(
            AutoModel::query()->paginate()
        );
    }

    /**
     * @param AutoModelDTO $data
     * @return JsonResource
     */
    public function create(AutoModelDTO $data): JsonResource
    {
        return new AutoModelResource(
            AutoModel::query()->firstOrCreate($data->toArray())
        );
    }

    /**
     * @param int $id
     * @param AutoModelDTO $data
     * @return JsonResource
     */
    public function update(int $id, AutoModelDTO $data): JsonResource
    {
        $mark = AutoModel::byId($id)->first();
        $mark->update($data->toArray());
        $mark->refresh();

        return new AutoModelResource(
            $mark
        );
    }

    /**
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        AutoModel::destroy($id);
    }
}
