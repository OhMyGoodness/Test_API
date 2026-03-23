<?php

declare(strict_types=1);

namespace App\Services\Auto\Interfaces;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoModelRequestDTO;
use App\Services\Auto\DTO\Response\AutoModelResponseDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Интерфейс сервиса для работы с моделями автомобилей.
 *
 * @package App\Services\Auto\Interfaces
 */
interface AutoModelServiceInterface
{
    /**
     * Получить список всех моделей автомобилей с пагинацией.
     *
     * @return LengthAwarePaginator
     * @throws ServiceException
     */
    public function list(): LengthAwarePaginator;

    /**
     * Найти модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели автомобиля.
     * @return AutoModelResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function find(int $id): AutoModelResponseDTO;

    /**
     * Создать новую модель автомобиля.
     *
     * @param AutoModelRequestDTO $data Данные для создания модели.
     * @return AutoModelResponseDTO
     * @throws ServiceException
     */
    public function create(AutoModelRequestDTO $data): AutoModelResponseDTO;

    /**
     * Обновить существующую модель автомобиля.
     *
     * @param int $id Идентификатор модели автомобиля.
     * @param AutoModelRequestDTO $data Данные для обновления модели.
     * @return AutoModelResponseDTO
     * @throws ResourceNotFoundException|ServiceException
     */
    public function update(int $id, AutoModelRequestDTO $data): AutoModelResponseDTO;

    /**
     * Удалить модель автомобиля по идентификатору.
     *
     * @param int $id Идентификатор модели автомобиля.
     * @return void
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): void;
}
