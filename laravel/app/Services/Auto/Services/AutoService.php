<?php

namespace App\Services\Auto\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use App\Services\Auto\DTO\Response\AutoResponseDTO;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\Auto\Models\Auto;
use App\Services\BaseResourceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Сервис для работы с автомобилями
 *
 * @package App\Services\Auto\Services
 */
class AutoService extends BaseResourceService implements AutoServiceInterface
{
    /**
     * Получить список всех автомобилей с пагинацией (интерфейсный метод).
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator
    {
        return $this->executeSafely(function () {
            return Auto::query()
                       ->with(['mark', 'model'])
                       ->orderBy('created_at', 'desc')
                       ->paginate();
        }, 'Не удалось получить список автомобилей');
    }

    /**
     * Получить список автомобилей по ID пользователя (интерфейсный метод).
     *
     * @param int $userId
     * @return Collection<AutoResponseDTO>
     * @throws ServiceException
     */
    public function listByUserId(int $userId): Collection
    {
        return $this->executeSafely(function () use ($userId) {
            $autos = Auto::byUserId($userId)
                         ->with(['mark', 'model'])
                         ->orderBy('created_at', 'desc')
                         ->get();

            return $autos->map(function ($auto) {
                return AutoResponseDTO::from($auto->toArray());
            });
        }, 'Не удалось получить список автомобилей пользователя');
    }

    /**
     * Найти автомобиль по ID и вернуть DTO.
     *
     * @param int $id
     * @param bool $withUserCheck Проверять принадлежность текущему пользователю
     * @return AutoResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id, bool $withUserCheck = false): AutoResponseDTO
    {
        return $this->executeSafely(function () use ($id, $withUserCheck) {
            $query = Auto::byId($id)
                         ->with(['mark', 'model']);

            if ($withUserCheck && Auth::check()) {
                $query->byUserId(Auth::id());
            }

            $auto = $query->firstOrFail();
            return AutoResponseDTO::from($auto->toArray());
        }, 'Не удалось найти автомобиль');
    }

    /**
     * Создать новый автомобиль (интерфейсный метод).
     *
     * @param AutoRequestDTO $data
     * @return AutoResponseDTO
     * @throws ServiceException
     */
    public function create(AutoRequestDTO $data): AutoResponseDTO
    {
        return $this->executeSafely(function () use ($data) {
            $autoData = $data->toArray();

            $autoData['user_id'] = Auth::id();

            $auto = Auto::create($autoData);
            $auto->load(['mark', 'model']);

            return AutoResponseDTO::from($auto);
        }, 'Не удалось создать автомобиль');
    }

    /**
     * Обновить существующий автомобиль (интерфейсный метод).
     *
     * @param int $id
     * @param AutoRequestDTO $data
     * @return AutoResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoRequestDTO $data): AutoResponseDTO
    {
        return $this->executeSafely(function () use ($id, $data) {
            $query = Auto::byId($id);

            if (Auth::check()) {
                $query->byUserId(Auth::id());
            }

            /** @var Auto $auto */
            $auto = $query->firstOrFail();

            $auto->update($data->toArray());
            $auto->load(['mark', 'model']);

            return AutoResponseDTO::from($auto->toArray());
        }, 'Не удалось обновить автомобиль');
    }

    /**
     * Удалить автомобиль.
     *
     * @param int $id
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void
    {
        $this->executeSafely(function () use ($id) {
            $query = Auto::byId($id);

            if (Auth::check()) {
                $query->byUserId(Auth::id());
            }

            $auto = $query->firstOrFail();
            $auto->delete();
        }, 'Не удалось удалить автомобиль');
    }

    /**
     * Получить список автомобилей текущего пользователя с пагинацией.
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function listByCurrentUser(): LengthAwarePaginator
    {
        return $this->executeSafely(function () {
            return Auto::byUserId(Auth::id())
                       ->with(['mark', 'model'])
                       ->orderBy('created_at', 'desc')
                       ->paginate();
        }, 'Не удалось получить список автомобилей пользователя');
    }
}
