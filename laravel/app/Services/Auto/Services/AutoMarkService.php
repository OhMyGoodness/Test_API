<?php

namespace App\Services\Auto\Services;

use App\Services\Auto\DTO\AutoMarkDTO;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Resources\AutoMarkResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @package App\Services\Auto\Services
 */
class AutoMarkService
{
    /**
     * @return ResourceCollection
     */
    public function list(): ResourceCollection
    {
        return AutoMarkResource::collection(
            AutoMark::query()->paginate()
        );
    }

    /**
     * @param AutoMarkDTO $data
     * @return JsonResource
     */
    public function create(AutoMarkDTO $data): JsonResource
    {
        return new AutoMarkResource(
            AutoMark::query()
                ->firstOrCreate($data->toArray())
        );
    }

    /**
     * @param int $id
     * @param AutoMarkDTO $data
     * @return JsonResource
     */
    public function update(int $id, AutoMarkDTO $data): JsonResource
    {
        $mark = AutoMark::byId($id)->first();
        $mark->update($data->toArray());
        $mark->refresh();

        return new AutoMarkResource(
            $mark
        );
    }

    /**
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        AutoMark::destroy($id);
    }
}
