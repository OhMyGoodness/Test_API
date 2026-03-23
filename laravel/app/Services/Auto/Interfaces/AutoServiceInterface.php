<?php

declare(strict_types=1);

namespace App\Services\Auto\Interfaces;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use App\Services\Auto\DTO\Response\AutoResponseDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Интерфейс сервиса для работы с автомобилями.
 *
 * @package App\Services\Auto\Interfaces
 */
interface AutoServiceInterface
{
    /**
     * Получить список всех автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator;

    /**
     * Получить список автомобилей по идентификатору пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return Collection<int, AutoResponseDTO>
     * @throws ServiceException
     */
    public function listByUserId(int $userId): Collection;

    /**
     * Найти автомобиль по идентификатору.
     *
     * @param int $id Идентификатор автомобиля.
     * @param bool $withUserCheck Проверять принадлежность текущему пользователю.
     * @return AutoResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id, bool $withUserCheck = false): AutoResponseDTO;

    /**
     * Создать новый автомобиль.
     *
     * @param AutoRequestDTO $data Данные для создания автомобиля.
     * @return AutoResponseDTO
     * @throws ServiceException
     */
    public function create(AutoRequestDTO $data): AutoResponseDTO;

    /**
     * Обновить существующий автомобиль.
     *
     * @param int $id Идентификатор автомобиля.
     * @param AutoRequestDTO $data Данные для обновления автомобиля.
     * @return AutoResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoRequestDTO $data): AutoResponseDTO;

    /**
     * Удалить автомобиль по идентификатору.
     *
     * @param int $id Идентификатор автомобиля.
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void;
}
