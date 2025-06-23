<?php

namespace App\Services\Auto\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Http\Responses\ApiResponse;
use App\Services\Auto\Services\AutoService;
use App\Services\Auto\Http\Requests\AutoRequest;
use App\Services\Auto\Resources\AutoResource;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления автомобилями
 */
class AutoController
{
    public function __construct(private readonly AutoService $autoService)
    {
    }

    /**
     * Получить список автомобилей с пагинацией
     *
     * @return JsonResponse
     * @throws ServiceException
     */
    public function index(): JsonResponse
    {
        $autos = $this->autoService->list();
        return ApiResponse::success(AutoResource::collection($autos));
    }

    /**
     * Создать новый автомобиль
     *
     * @param AutoRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    public function store(AutoRequest $request): JsonResponse
    {
        $autoResponseDTO = $this->autoService->create($request->getDto());
        return ApiResponse::created(new AutoResource($autoResponseDTO));
    }

    /**
     * Получить автомобиль по ID
     *
     * @param int $id
     * @return JsonResponse
     * @throws ServiceException|ResourceNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        $autoResponseDTO = $this->autoService->find($id);
        return ApiResponse::success(new AutoResource($autoResponseDTO));
    }

    /**
     * Обновить автомобиль
     *
     * @param int $id
     * @param AutoRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    public function update(int $id, AutoRequest $request): JsonResponse
    {
        $autoResponseDTO = $this->autoService->update($id, $request->getDTO());
        return ApiResponse::success(new AutoResource($autoResponseDTO));
    }

    /**
     * Удалить автомобиль
     *
     * @param int $id
     * @return JsonResponse
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->autoService->destroy($id);
        return ApiResponse::noContent();
    }

    protected function getRequestClass(): string
    {
        return AutoRequest::class;
    }
}
