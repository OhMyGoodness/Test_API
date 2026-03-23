<?php

declare(strict_types=1);

namespace App\Services\Auto\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use App\Services\Auto\DTO\Response\AutoResponseDTO;
use App\Services\Auto\Interfaces\AutoRepositoryInterface;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\BaseResourceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с автомобилями.
 *
 * Реализует бизнес-логику управления автомобилями, делегируя
 * работу с базой данных в репозиторий.
 *
 * @package App\Services\Auto\Services
 */
class AutoService extends BaseResourceService implements AutoServiceInterface
{
    /**
     * @param AutoRepositoryInterface $repository Репозиторий автомобилей.
     */
    public function __construct(
        private readonly AutoRepositoryInterface $repository,
    ) {}

    /**
     * Получить список всех автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список автомобилей.
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator
    {
        return $this->executeSafely(
            fn () => $this->repository->list(),
            'Не удалось получить список автомобилей',
        );
    }

    /**
     * Получить список автомобилей по ID пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return Collection<int, AutoResponseDTO> Коллекция DTO автомобилей.
     * @throws ServiceException
     */
    public function listByUserId(int $userId): Collection
    {
        return $this->executeSafely(function () use ($userId) {
            $autos = $this->repository->findByUserId($userId);

            return $autos->map(
                fn ($auto) => AutoResponseDTO::from($auto->toArray()),
            );
        }, 'Не удалось получить список автомобилей пользователя');
    }

    /**
     * Найти автомобиль по ID и вернуть DTO.
     *
     * @param int  $id            Идентификатор автомобиля.
     * @param bool $withUserCheck Проверять принадлежность текущему пользователю.
     * @return AutoResponseDTO DTO найденного автомобиля.
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id, bool $withUserCheck = false): AutoResponseDTO
    {
        return $this->executeSafely(function () use ($id, $withUserCheck) {
            if ($withUserCheck && Auth::check()) {
                $auto = $this->repository->findByIdForUser($id, (int) Auth::id());
            } else {
                $auto = $this->repository->find($id);
            }

            if ($auto === null) {
                throw new ResourceNotFoundException('Resource');
            }

            return AutoResponseDTO::from($auto->toArray());
        }, 'Не удалось найти автомобиль');
    }

    /**
     * Создать новый автомобиль.
     *
     * @param AutoRequestDTO $data DTO с данными для создания.
     * @return AutoResponseDTO DTO созданного автомобиля.
     * @throws ServiceException
     */
    public function create(AutoRequestDTO $data): AutoResponseDTO
    {
        return $this->executeSafely(function () use ($data) {
            return DB::transaction(function () use ($data) {
                $autoData             = $data->toArray();
                $autoData['user_id']  = Auth::id();

                $auto = $this->repository->create($autoData);

                Log::info("AutoService.create: Auto '{$auto->id}' successfully created for user '{$autoData['user_id']}'");

                return AutoResponseDTO::from($auto->toArray());
            });
        }, 'Не удалось создать автомобиль');
    }

    /**
     * Обновить существующий автомобиль.
     *
     * @param int            $id   Идентификатор автомобиля.
     * @param AutoRequestDTO $data DTO с данными для обновления.
     * @return AutoResponseDTO DTO обновлённого автомобиля.
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoRequestDTO $data): AutoResponseDTO
    {
        return $this->executeSafely(function () use ($id, $data) {
            return DB::transaction(function () use ($id, $data) {
                if (Auth::check()) {
                    $auto = $this->repository->updateForUser($id, (int) Auth::id(), $data->toArray());
                } else {
                    $auto = $this->repository->update($id, $data->toArray());
                }

                if ($auto === null) {
                    throw new ResourceNotFoundException('Resource');
                }

                Log::info("AutoService.update: Auto '{$id}' successfully updated");

                return AutoResponseDTO::from($auto->toArray());
            });
        }, 'Не удалось обновить автомобиль');
    }

    /**
     * Удалить автомобиль.
     *
     * @param int $id Идентификатор автомобиля.
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void
    {
        $this->executeSafely(function () use ($id) {
            DB::transaction(function () use ($id) {
                if (Auth::check()) {
                    $deleted = $this->repository->deleteForUser($id, (int) Auth::id());
                } else {
                    $deleted = $this->repository->delete($id);
                }

                if (! $deleted) {
                    throw new ResourceNotFoundException('Resource');
                }

                Log::info("AutoService.destroy: Auto '{$id}' successfully deleted");
            });
        }, 'Не удалось удалить автомобиль');
    }

    /**
     * Получить список автомобилей текущего авторизованного пользователя с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список автомобилей.
     * @throws ServiceException
     */
    public function listByCurrentUser(): LengthAwarePaginator
    {
        return $this->executeSafely(
            fn () => $this->repository->paginateByUserId((int) Auth::id()),
            'Не удалось получить список автомобилей пользователя',
        );
    }
}
