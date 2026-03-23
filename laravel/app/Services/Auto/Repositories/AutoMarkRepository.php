<?php

declare(strict_types=1);

namespace App\Services\Auto\Repositories;

use App\Services\Auto\Interfaces\AutoMarkRepositoryInterface;
use App\Services\Auto\Models\AutoMark;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Репозиторий для работы с марками автомобилей через Eloquent.
 */
class AutoMarkRepository implements AutoMarkRepositoryInterface
{
    /**
     * Получить список всех марок автомобилей с пагинацией.
     *
     * Сортирует марки по названию в алфавитном порядке.
     *
     * @return LengthAwarePaginator Пагинированный список марок.
     */
    public function list(): LengthAwarePaginator
    {
        return AutoMark::query()
            ->orderBy('name')
            ->paginate();
    }

    /**
     * Найти марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки.
     * @return AutoMark|null Найденная марка или null.
     */
    public function find(int $id): ?AutoMark
    {
        return AutoMark::query()
            ->byId($id)
            ->first();
    }

    /**
     * Создать новую марку автомобиля.
     *
     * @param array<string, mixed> $data Данные для создания марки.
     * @return AutoMark Созданная марка.
     */
    public function create(array $data): AutoMark
    {
        return AutoMark::create($data);
    }

    /**
     * Обновить существующую марку автомобиля по идентификатору.
     *
     * После обновления возвращает свежие данные из БД.
     *
     * @param int $id Идентификатор марки.
     * @param array<string, mixed> $data Данные для обновления.
     * @return AutoMark|null Обновлённая марка или null, если не найдена.
     */
    public function update(int $id, array $data): ?AutoMark
    {
        $mark = AutoMark::query()->byId($id)->first();

        if ($mark === null) {
            return null;
        }

        $mark->update($data);

        return $mark->fresh();
    }

    /**
     * Удалить марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки.
     * @return bool true — успешно удалена, false — не найдена.
     */
    public function delete(int $id): bool
    {
        $mark = AutoMark::query()->byId($id)->first();

        if ($mark === null) {
            return false;
        }

        $mark->delete();

        return true;
    }

    /**
     * Проверить, есть ли автомобили, привязанные к данной марке.
     *
     * @param int $markId Идентификатор марки автомобиля.
     * @return bool true — есть привязанные автомобили, false — нет.
     */
    public function hasRelatedAutos(int $markId): bool
    {
        $mark = AutoMark::query()->byId($markId)->first();

        if ($mark === null) {
            return false;
        }

        return $mark->autos()->exists();
    }
}
