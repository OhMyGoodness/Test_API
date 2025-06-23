<?php

namespace App\Services\Auto\Interfaces;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use App\Services\Auto\DTO\Response\AutoResponseDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Интерфейс для сервиса работы с автомобилями
 *
 * @package App\Services\Auto\Interfaces
 */
interface AutoServiceInterface
{
    /**
     * Получить список всех автомобилей с пагинацией
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator;

    /**
     * Получить список автомобилей по ID пользователя
     *
     * @param int $userId
     * @return Collection<AutoResponseDTO>
     * @throws ServiceException
     */
    public function listByUserId(int $userId): Collection;

    /**
     * Создать новый автомобиль
     *
     * @param AutoRequestDTO $data
     * @return AutoResponseDTO
     * @throws ServiceException
     */
    public function create(AutoRequestDTO $data): AutoResponseDTO;

    /**
     * Обновить существующий автомобиль
     *
     * @param int $id
     * @param AutoRequestDTO $data
     * @return AutoResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoRequestDTO $data): AutoResponseDTO;

    /**
     * Удалить автомобиль
     *
     * @param int $id
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void;
}
