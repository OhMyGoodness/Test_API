<?php

declare(strict_types=1);

namespace App\Services\Auto\Interfaces;

use App\Services\Auto\Models\AutoMark;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Интерфейс репозитория для работы с марками автомобилей.
 */
interface AutoMarkRepositoryInterface
{
    /**
     * Получить список всех марок автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список марок.
     */
    public function list(): LengthAwarePaginator;

    /**
     * Найти марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки.
     * @return AutoMark|null Найденная марка или null.
     */
    public function find(int $id): ?AutoMark;

    /**
     * Создать новую марку автомобиля.
     *
     * @param array<string, mixed> $data Данные для создания марки.
     * @return AutoMark Созданная марка.
     */
    public function create(array $data): AutoMark;

    /**
     * Обновить существующую марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки.
     * @param array<string, mixed> $data Данные для обновления.
     * @return AutoMark|null Обновлённая марка или null, если не найдена.
     */
    public function update(int $id, array $data): ?AutoMark;

    /**
     * Удалить марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки.
     * @return bool true — успешно удалена, false — не найдена.
     */
    public function delete(int $id): bool;

    /**
     * Проверить, есть ли автомобили, привязанные к данной марке.
     *
     * @param int $markId Идентификатор марки автомобиля.
     * @return bool true — есть привязанные автомобили, false — нет.
     */
    public function hasRelatedAutos(int $markId): bool;
}
