<?php

declare(strict_types=1);

namespace App\Services\Auto\Repositories;

use App\Services\Auto\Interfaces\AutoRepositoryInterface;
use App\Services\Auto\Models\Auto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для работы с автомобилями через Eloquent.
 */
class AutoRepository implements AutoRepositoryInterface
{
    /**
     * Получить список всех автомобилей с пагинацией.
     *
     * Загружает связанные марку и модель автомобиля, сортирует по дате создания (новые первые).
     *
     * @return LengthAwarePaginator Пагинированный список автомобилей.
     */
    public function list(): LengthAwarePaginator
    {
        return Auto::query()
            ->with(['mark', 'model'])
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    /**
     * Найти автомобиль по идентификатору.
     *
     * Загружает связанные марку и модель автомобиля.
     *
     * @param int $id Идентификатор автомобиля.
     * @return Auto|null Найденный автомобиль или null.
     */
    public function find(int $id): ?Auto
    {
        return Auto::query()
            ->with(['mark', 'model'])
            ->byId($id)
            ->first();
    }

    /**
     * Создать новый автомобиль.
     *
     * После создания загружает связанные марку и модель.
     *
     * @param array<string, mixed> $data Данные для создания автомобиля.
     * @return Auto Созданный автомобиль со связями.
     */
    public function create(array $data): Auto
    {
        $auto = Auto::create($data);
        $auto->load(['mark', 'model']);

        return $auto;
    }

    /**
     * Обновить существующий автомобиль по идентификатору.
     *
     * После обновления перезагружает связанные марку и модель.
     *
     * @param int $id Идентификатор автомобиля.
     * @param array<string, mixed> $data Данные для обновления.
     * @return Auto|null Обновлённый автомобиль или null, если не найден.
     */
    public function update(int $id, array $data): ?Auto
    {
        $auto = Auto::query()->byId($id)->first();

        if ($auto === null) {
            return null;
        }

        $auto->update($data);
        $auto->load(['mark', 'model']);

        return $auto;
    }

    /**
     * Удалить автомобиль по идентификатору.
     *
     * @param int $id Идентификатор автомобиля.
     * @return bool true — успешно удалён, false — не найден.
     */
    public function delete(int $id): bool
    {
        $auto = Auto::query()->byId($id)->first();

        if ($auto === null) {
            return false;
        }

        $auto->delete();

        return true;
    }

    /**
     * Получить список автомобилей пользователя по его идентификатору.
     *
     * Загружает связанные марку и модель, сортирует по дате создания (новые первые).
     *
     * @param int $userId Идентификатор пользователя.
     * @return Collection<int, Auto> Коллекция автомобилей пользователя.
     */
    public function findByUserId(int $userId): Collection
    {
        return Auto::query()
            ->byUserId($userId)
            ->with(['mark', 'model'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Получить список автомобилей пользователя с пагинацией.
     *
     * Загружает связанные марку и модель, сортирует по дате создания (новые первые).
     *
     * @param int $userId Идентификатор пользователя.
     * @return LengthAwarePaginator Пагинированный список автомобилей пользователя.
     */
    public function paginateByUserId(int $userId): LengthAwarePaginator
    {
        return Auto::query()
            ->byUserId($userId)
            ->with(['mark', 'model'])
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    /**
     * Найти автомобиль по идентификатору с проверкой принадлежности пользователю.
     *
     * Загружает связанные марку и модель.
     *
     * @param int $id     Идентификатор автомобиля.
     * @param int $userId Идентификатор пользователя-владельца.
     * @return Auto|null Найденный автомобиль или null, если не найден или не принадлежит пользователю.
     */
    public function findByIdForUser(int $id, int $userId): ?Auto
    {
        return Auto::query()
            ->byId($id)
            ->byUserId($userId)
            ->with(['mark', 'model'])
            ->first();
    }

    /**
     * Обновить автомобиль, принадлежащий указанному пользователю.
     *
     * После обновления перезагружает связанные марку и модель.
     *
     * @param int                  $id     Идентификатор автомобиля.
     * @param int                  $userId Идентификатор пользователя-владельца.
     * @param array<string, mixed> $data   Данные для обновления.
     * @return Auto|null Обновлённый автомобиль или null, если не найден или не принадлежит пользователю.
     */
    public function updateForUser(int $id, int $userId, array $data): ?Auto
    {
        $auto = Auto::query()->byId($id)->byUserId($userId)->first();

        if ($auto === null) {
            return null;
        }

        $auto->update($data);
        $auto->load(['mark', 'model']);

        return $auto;
    }

    /**
     * Удалить автомобиль, принадлежащий указанному пользователю.
     *
     * @param int $id     Идентификатор автомобиля.
     * @param int $userId Идентификатор пользователя-владельца.
     * @return bool true — успешно удалён, false — не найден или не принадлежит пользователю.
     */
    public function deleteForUser(int $id, int $userId): bool
    {
        $auto = Auto::query()->byId($id)->byUserId($userId)->first();

        if ($auto === null) {
            return false;
        }

        $auto->delete();

        return true;
    }
}
