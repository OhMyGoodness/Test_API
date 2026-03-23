<?php

declare(strict_types=1);

namespace App\Services\Auto\Interfaces;

use App\Services\Auto\Models\Auto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Интерфейс репозитория для работы с автомобилями.
 */
interface AutoRepositoryInterface
{
    /**
     * Получить список всех автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator Пагинированный список автомобилей.
     */
    public function list(): LengthAwarePaginator;

    /**
     * Найти автомобиль по идентификатору.
     *
     * @param int $id Идентификатор автомобиля.
     * @return Auto|null Найденный автомобиль или null.
     */
    public function find(int $id): ?Auto;

    /**
     * Создать новый автомобиль.
     *
     * @param array<string, mixed> $data Данные для создания автомобиля.
     * @return Auto Созданный автомобиль.
     */
    public function create(array $data): Auto;

    /**
     * Обновить существующий автомобиль по идентификатору.
     *
     * @param int $id Идентификатор автомобиля.
     * @param array<string, mixed> $data Данные для обновления.
     * @return Auto|null Обновлённый автомобиль или null, если не найден.
     */
    public function update(int $id, array $data): ?Auto;

    /**
     * Удалить автомобиль по идентификатору.
     *
     * @param int $id Идентификатор автомобиля.
     * @return bool true — успешно удалён, false — не найден.
     */
    public function delete(int $id): bool;

    /**
     * Получить список автомобилей пользователя по его идентификатору.
     *
     * @param int $userId Идентификатор пользователя.
     * @return Collection<int, Auto> Коллекция автомобилей пользователя.
     */
    public function findByUserId(int $userId): Collection;

    /**
     * Получить список автомобилей пользователя с пагинацией.
     *
     * @param int $userId Идентификатор пользователя.
     * @return LengthAwarePaginator Пагинированный список автомобилей пользователя.
     */
    public function paginateByUserId(int $userId): LengthAwarePaginator;

    /**
     * Найти автомобиль по идентификатору с проверкой принадлежности пользователю.
     *
     * @param int $id     Идентификатор автомобиля.
     * @param int $userId Идентификатор пользователя-владельца.
     * @return Auto|null Найденный автомобиль или null, если не найден или не принадлежит пользователю.
     */
    public function findByIdForUser(int $id, int $userId): ?Auto;

    /**
     * Обновить автомобиль, принадлежащий указанному пользователю.
     *
     * @param int                  $id     Идентификатор автомобиля.
     * @param int                  $userId Идентификатор пользователя-владельца.
     * @param array<string, mixed> $data   Данные для обновления.
     * @return Auto|null Обновлённый автомобиль или null, если не найден или не принадлежит пользователю.
     */
    public function updateForUser(int $id, int $userId, array $data): ?Auto;

    /**
     * Удалить автомобиль, принадлежащий указанному пользователю.
     *
     * @param int $id     Идентификатор автомобиля.
     * @param int $userId Идентификатор пользователя-владельца.
     * @return bool true — успешно удалён, false — не найден или не принадлежит пользователю.
     */
    public function deleteForUser(int $id, int $userId): bool;
}
