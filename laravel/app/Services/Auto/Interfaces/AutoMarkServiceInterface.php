<?php

declare(strict_types=1);

namespace App\Services\Auto\Interfaces;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use App\Services\Auto\DTO\Response\AutoMarkResponseDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Интерфейс сервиса для работы с марками автомобилей.
 *
 * @package App\Services\Auto\Interfaces
 */
interface AutoMarkServiceInterface
{
    /**
     * Получить список марок автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator;

    /**
     * Найти марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки автомобиля.
     * @return AutoMarkResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id): AutoMarkResponseDTO;

    /**
     * Создать новую марку автомобиля.
     *
     * @param AutoMarkRequestDTO $data Данные для создания марки.
     * @return AutoMarkResponseDTO
     * @throws ServiceException
     */
    public function create(AutoMarkRequestDTO $data): AutoMarkResponseDTO;

    /**
     * Обновить существующую марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки автомобиля.
     * @param AutoMarkRequestDTO $data Данные для обновления марки.
     * @return AutoMarkResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoMarkRequestDTO $data): AutoMarkResponseDTO;

    /**
     * Удалить марку автомобиля по идентификатору.
     *
     * @param int $id Идентификатор марки автомобиля.
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void;
}
