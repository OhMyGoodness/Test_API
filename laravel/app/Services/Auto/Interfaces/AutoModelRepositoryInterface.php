<?php

declare(strict_types=1);

namespace App\Services\Auto\Interfaces;

use App\Services\Auto\Models\AutoModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Интерфейс репозитория для работы с моделями автомобилей.
 */
interface AutoModelRepositoryInterface
{
    /**
     * Получить список всех моделей автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список моделей.
     */
    public function list(): LengthAwarePaginator;

    /**
     * Найти модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели.
     * @return AutoModel|null Найденная модель или null.
     */
    public function find(int $id): ?AutoModel;

    /**
     * Создать новую модель автомобиля.
     *
     * @param array<string, mixed> $data Данные для создания модели.
     * @return AutoModel Созданная модель.
     */
    public function create(array $data): AutoModel;

    /**
     * Обновить существующую модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели.
     * @param array<string, mixed> $data Данные для обновления.
     * @return AutoModel|null Обновлённая модель или null, если не найдена.
     */
    public function update(int $id, array $data): ?AutoModel;

    /**
     * Удалить модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели.
     * @return bool true — успешно удалена, false — не найдена.
     */
    public function delete(int $id): bool;

    /**
     * Проверить, есть ли автомобили, привязанные к данной модели.
     *
     * @param int $modelId Идентификатор модели автомобиля.
     * @return bool true — есть привязанные автомобили, false — нет.
     */
    public function hasRelatedAutos(int $modelId): bool;
}
