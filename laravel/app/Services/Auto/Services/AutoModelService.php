<?php

declare(strict_types=1);

namespace App\Services\Auto\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoModelRequestDTO;
use App\Services\Auto\DTO\Response\AutoModelResponseDTO;
use App\Services\Auto\Interfaces\AutoModelRepositoryInterface;
use App\Services\Auto\Interfaces\AutoModelServiceInterface;
use App\Services\BaseResourceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с моделями автомобилей.
 *
 * Реализует бизнес-логику управления моделями автомобилей, делегируя
 * работу с базой данных в репозиторий.
 *
 * @package App\Services\Auto\Services
 */
class AutoModelService extends BaseResourceService implements AutoModelServiceInterface
{
    /**
     * @param AutoModelRepositoryInterface $repository Репозиторий моделей автомобилей.
     */
    public function __construct(
        private readonly AutoModelRepositoryInterface $repository,
    ) {}

    /**
     * Получить список всех моделей автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список моделей.
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator
    {
        return $this->executeSafely(
            fn () => $this->repository->list(),
            'Не удалось получить список моделей автомобилей',
        );
    }

    /**
     * Найти модель автомобиля по ID и вернуть DTO.
     *
     * @param int $id Идентификатор модели.
     * @return AutoModelResponseDTO DTO найденной модели.
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id): AutoModelResponseDTO
    {
        return $this->executeSafely(function () use ($id) {
            $model = $this->repository->find($id);

            if ($model === null) {
                throw new ResourceNotFoundException('Resource');
            }

            return AutoModelResponseDTO::from($model->toArray());
        }, 'Не удалось найти модель автомобиля');
    }

    /**
     * Создать новую модель автомобиля.
     *
     * @param AutoModelRequestDTO $data DTO с данными для создания.
     * @return AutoModelResponseDTO DTO созданной модели.
     * @throws ServiceException
     */
    public function create(AutoModelRequestDTO $data): AutoModelResponseDTO
    {
        return $this->executeSafely(function () use ($data) {
            return DB::transaction(function () use ($data) {
                $model = $this->repository->create($data->toArray());

                Log::info("AutoModelService.create: Auto model '{$model->id}' successfully created");

                return AutoModelResponseDTO::from($model->toArray());
            });
        }, 'Не удалось создать модель автомобиля');
    }

    /**
     * Обновить существующую модель автомобиля.
     *
     * @param int                 $id   Идентификатор модели.
     * @param AutoModelRequestDTO $data DTO с данными для обновления.
     * @return AutoModelResponseDTO DTO обновлённой модели.
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoModelRequestDTO $data): AutoModelResponseDTO
    {
        return $this->executeSafely(function () use ($id, $data) {
            return DB::transaction(function () use ($id, $data) {
                $model = $this->repository->update($id, $data->toArray());

                if ($model === null) {
                    throw new ResourceNotFoundException('Resource');
                }

                Log::info("AutoModelService.update: Auto model '{$id}' successfully updated");

                return AutoModelResponseDTO::from($model->toArray());
            });
        }, 'Не удалось обновить модель автомобиля');
    }

    /**
     * Удалить модель автомобиля.
     *
     * Перед удалением проверяет отсутствие привязанных автомобилей.
     * Если к модели привязаны автомобили — выбрасывает ServiceException с кодом 409.
     *
     * @param int $id Идентификатор модели автомобиля.
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void
    {
        $this->executeSafely(function () use ($id) {
            DB::transaction(function () use ($id) {
                $model = $this->repository->find($id);

                if ($model === null) {
                    throw new ResourceNotFoundException('Resource');
                }

                if ($this->repository->hasRelatedAutos($id)) {
                    throw new ServiceException(
                        'Невозможно удалить модель автомобиля, так как к ней привязаны автомобили',
                        Response::HTTP_CONFLICT,
                    );
                }

                $this->repository->delete($id);

                Log::info("AutoModelService.destroy: Auto model '{$id}' successfully deleted");
            });
        }, 'Не удалось удалить модель автомобиля');
    }
}
