<?php

declare(strict_types=1);

namespace App\Services\Auto\Repositories;

use App\Services\Auto\Interfaces\AutoModelRepositoryInterface;
use App\Services\Auto\Models\AutoModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Репозиторий для работы с моделями автомобилей через Eloquent.
 */
class AutoModelRepository implements AutoModelRepositoryInterface
{
    /**
     * Получить список всех моделей автомобилей с пагинацией.
     *
     * Сортирует модели по названию в алфавитном порядке.
     *
     * @return LengthAwarePaginator Пагинированный список моделей.
     */
    public function list(): LengthAwarePaginator
    {
        return AutoModel::query()
            ->orderBy('name')
            ->paginate();
    }

    /**
     * Найти модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели.
     * @return AutoModel|null Найденная модель или null.
     */
    public function find(int $id): ?AutoModel
    {
        return AutoModel::query()
            ->byId($id)
            ->first();
    }

    /**
     * Создать новую модель автомобиля.
     *
     * @param array<string, mixed> $data Данные для создания модели.
     * @return AutoModel Созданная модель.
     */
    public function create(array $data): AutoModel
    {
        return AutoModel::create($data);
    }

    /**
     * Обновить существующую модель автомобиля по идентификатору.
     *
     * После обновления возвращает свежие данные из БД.
     *
     * @param int $id Идентификатор модели.
     * @param array<string, mixed> $data Данные для обновления.
     * @return AutoModel|null Обновлённая модель или null, если не найдена.
     */
    public function update(int $id, array $data): ?AutoModel
    {
        $model = AutoModel::query()->byId($id)->first();

        if ($model === null) {
            return null;
        }

        $model->update($data);

        return $model->fresh();
    }

    /**
     * Удалить модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели.
     * @return bool true — успешно удалена, false — не найдена.
     */
    public function delete(int $id): bool
    {
        $model = AutoModel::query()->byId($id)->first();

        if ($model === null) {
            return false;
        }

        $model->delete();

        return true;
    }

    /**
     * Проверить, есть ли автомобили, привязанные к данной модели.
     *
     * @param int $modelId Идентификатор модели автомобиля.
     * @return bool true — есть привязанные автомобили, false — нет.
     */
    public function hasRelatedAutos(int $modelId): bool
    {
        $model = AutoModel::query()->byId($modelId)->first();

        if ($model === null) {
            return false;
        }

        return $model->autos()->exists();
    }
}
