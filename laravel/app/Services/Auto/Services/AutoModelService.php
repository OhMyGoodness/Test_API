<?php

namespace App\Services\Auto\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoModelRequestDTO;
use App\Services\Auto\DTO\Response\AutoModelResponseDTO;
use App\Services\Auto\Models\AutoModel;
use App\Services\BaseResourceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Сервис для работы с моделями автомобилей
 *
 * @package App\Services\Auto\Services
 */
class AutoModelService extends BaseResourceService
{
    /**
     * Получить список всех моделей автомобилей с пагинацией
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator
    {
        return $this->executeSafely(function () {
            return AutoModel::query()
                ->orderBy('name')
                ->paginate();
        }, 'Не удалось получить список моделей автомобилей');
    }

    /**
     * Найти модель автомобиля по ID и вернуть DTO
     *
     * @param int $id
     * @return AutoModelResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id): AutoModelResponseDTO
    {
        return $this->executeSafely(function () use ($id) {
            $model = AutoModel::query()
                ->byId($id)
                ->firstOrFail();

            return AutoModelResponseDTO::from($model->toArray());
        }, 'Не удалось найти модель автомобиля');
    }

    /**
     * Создать новую модель автомобиля
     *
     * @param AutoModelRequestDTO $data
     * @return AutoModelResponseDTO
     * @throws ServiceException
     */
    public function create(AutoModelRequestDTO $data): AutoModelResponseDTO
    {
        return $this->executeSafely(function () use ($data) {
            return DB::transaction(function () use ($data) {
                $model = AutoModel::create($data->toArray());
                return AutoModelResponseDTO::from($model->toArray());
            });
        }, 'Не удалось создать модель автомобиля');
    }

    /**
     * Обновить существующую модель автомобиля
     *
     * @param int $id
     * @param AutoModelRequestDTO $data
     * @return AutoModelResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoModelRequestDTO $data): AutoModelResponseDTO
    {
        return $this->executeSafely(function () use ($id, $data) {
            return DB::transaction(function () use ($id, $data) {
                $model = AutoModel::query()
                    ->byId($id)
                    ->firstOrFail();

                $model->update($data->toArray());

                return AutoModelResponseDTO::from($model->fresh()->toArray());
            });
        }, 'Не удалось обновить модель автомобиля');
    }

    /**
     * Удалить модель автомобиля
     *
     * @param int $id
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void
    {
        $this->executeSafely(function () use ($id) {
            return DB::transaction(function () use ($id) {
                $model = AutoModel::query()
                    ->byId($id)
                    ->firstOrFail();

                // Проверяем, есть ли связанные автомобили
                if ($model->autos()->exists()) {
                    throw new ServiceException(
                        'Невозможно удалить модель автомобиля, так как к ней привязаны автомобили',
                        Response::HTTP_CONFLICT
                    );
                }

                $model->delete();
            });
        }, 'Не удалось удалить модель автомобиля');
    }
}
