<?php

namespace App\Services\Auto\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use App\Services\Auto\DTO\Response\AutoMarkResponseDTO;
use App\Services\Auto\Models\AutoMark;
use App\Services\BaseResourceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Сервис для работы с марками автомобилей
 *
 * @package App\Services\Auto\Services
 */
class AutoMarkService extends BaseResourceService
{
    /**
     * Получить список марок автомобилей с пагинацией
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator
    {
        return $this->executeSafely(function () {
            return AutoMark::query()
                ->orderBy('name')
                ->paginate();
        }, 'Не удалось получить список марок автомобилей');
    }

    /**
     * Найти марку автомобиля по ID и вернуть DTO
     *
     * @param int $id
     * @return AutoMarkResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id): AutoMarkResponseDTO
    {
        return $this->executeSafely(function () use ($id) {
            $mark = AutoMark::query()
                ->byId($id)
                ->firstOrFail();

            return AutoMarkResponseDTO::from($mark->toArray());
        }, 'Не удалось найти марку автомобиля');
    }

    /**
     * Создать новую марку автомобиля
     *
     * @param AutoMarkRequestDTO $data
     * @return AutoMarkResponseDTO
     * @throws ServiceException
     */
    public function create(AutoMarkRequestDTO $data): AutoMarkResponseDTO
    {
        return $this->executeSafely(function () use ($data) {
            $mark = AutoMark::create($data->toArray());
            return AutoMarkResponseDTO::from($mark->toArray());
        }, 'Не удалось создать марку автомобиля');
    }

    /**
     * Обновить существующую марку по ID
     *
     * @param int $id
     * @param AutoMarkRequestDTO $data
     * @return AutoMarkResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoMarkRequestDTO $data): AutoMarkResponseDTO
    {
        return $this->executeSafely(function () use ($id, $data) {
            $mark = AutoMark::query()
                ->byId($id)
                ->firstOrFail();

            $mark->update($data->toArray());

            return AutoMarkResponseDTO::from($mark->fresh()->toArray());
        }, 'Не удалось обновить марку автомобиля');
    }

    /**
     * Удалить марку по ID
     *
     * @param int $id
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void
    {
        $this->executeSafely(function () use ($id) {
            $mark = AutoMark::query()
                ->byId($id)
                ->firstOrFail();

            $mark->delete();
        }, 'Не удалось удалить марку автомобиля');
    }
}
