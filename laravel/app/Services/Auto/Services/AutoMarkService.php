<?php

declare(strict_types=1);

namespace App\Services\Auto\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use App\Services\Auto\DTO\Response\AutoMarkResponseDTO;
use App\Services\Auto\Interfaces\AutoMarkRepositoryInterface;
use App\Services\Auto\Interfaces\AutoMarkServiceInterface;
use App\Services\BaseResourceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с марками автомобилей.
 *
 * Реализует бизнес-логику управления марками, делегируя
 * работу с базой данных в репозиторий.
 *
 * @package App\Services\Auto\Services
 */
class AutoMarkService extends BaseResourceService implements AutoMarkServiceInterface
{
    /**
     * @param AutoMarkRepositoryInterface $repository Репозиторий марок автомобилей.
     */
    public function __construct(
        private readonly AutoMarkRepositoryInterface $repository,
    ) {}

    /**
     * Получить список марок автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список марок.
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator
    {
        return $this->executeSafely(
            fn () => $this->repository->list(),
            'Не удалось получить список марок автомобилей',
        );
    }

    /**
     * Найти марку автомобиля по ID и вернуть DTO.
     *
     * @param int $id Идентификатор марки.
     * @return AutoMarkResponseDTO DTO найденной марки.
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id): AutoMarkResponseDTO
    {
        return $this->executeSafely(function () use ($id) {
            $mark = $this->repository->find($id);

            if ($mark === null) {
                throw new ResourceNotFoundException('Resource');
            }

            return AutoMarkResponseDTO::from($mark->toArray());
        }, 'Не удалось найти марку автомобиля');
    }

    /**
     * Создать новую марку автомобиля.
     *
     * @param AutoMarkRequestDTO $data DTO с данными для создания.
     * @return AutoMarkResponseDTO DTO созданной марки.
     * @throws ServiceException
     */
    public function create(AutoMarkRequestDTO $data): AutoMarkResponseDTO
    {
        return $this->executeSafely(function () use ($data) {
            return DB::transaction(function () use ($data) {
                $mark = $this->repository->create($data->toArray());

                Log::info("AutoMarkService.create: Auto mark '{$mark->id}' successfully created");

                return AutoMarkResponseDTO::from($mark->toArray());
            });
        }, 'Не удалось создать марку автомобиля');
    }

    /**
     * Обновить существующую марку по ID.
     *
     * @param int                $id   Идентификатор марки.
     * @param AutoMarkRequestDTO $data DTO с данными для обновления.
     * @return AutoMarkResponseDTO DTO обновлённой марки.
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoMarkRequestDTO $data): AutoMarkResponseDTO
    {
        return $this->executeSafely(function () use ($id, $data) {
            return DB::transaction(function () use ($id, $data) {
                $mark = $this->repository->update($id, $data->toArray());

                if ($mark === null) {
                    throw new ResourceNotFoundException('Resource');
                }

                Log::info("AutoMarkService.update: Auto mark '{$id}' successfully updated");

                return AutoMarkResponseDTO::from($mark->toArray());
            });
        }, 'Не удалось обновить марку автомобиля');
    }

    /**
     * Удалить марку по ID.
     *
     * Перед удалением проверяет отсутствие привязанных автомобилей.
     * Если к марке привязаны автомобили — выбрасывает ServiceException с кодом 409.
     *
     * @param int $id Идентификатор марки автомобиля.
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void
    {
        $this->executeSafely(function () use ($id) {
            DB::transaction(function () use ($id) {
                $mark = $this->repository->find($id);

                if ($mark === null) {
                    throw new ResourceNotFoundException('Resource');
                }

                if ($this->repository->hasRelatedAutos($id)) {
                    throw new ServiceException(
                        'Невозможно удалить марку автомобиля, так как к ней привязаны автомобили',
                        Response::HTTP_CONFLICT,
                    );
                }

                $this->repository->delete($id);

                Log::info("AutoMarkService.destroy: Auto mark '{$id}' successfully deleted");
            });
        }, 'Не удалось удалить марку автомобиля');
    }
}
