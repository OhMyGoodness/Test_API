<?php

namespace App\Services\Auto\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Http\Responses\ApiResponse;
use App\Services\Auto\Http\Requests\AutoModelRequest;
use App\Services\Auto\Resources\AutoModelResource;
use App\Services\Auto\Services\AutoModelService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления моделями автомобилей
 *
 * @package App\Services\Auto\Http\Controllers
 */
class AutoModelController
{
    public function __construct(private readonly AutoModelService $autoModelService)
    {
    }

    /**
     * Получить список моделей автомобилей с пагинацией
     *
     * @OA\Get(
     *   path="/auto_model",
     *   tags={"Auto Model"},
     *   summary="Получить список моделей автомобилей",
     *   description="Возвращает пагинированный список всех моделей автомобилей",
     *   @OA\Response(
     *       response=200,
     *       description="Успешный ответ",
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="success", type="boolean", example=true),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="data", type="array",
     *                   @OA\Items(ref="#/components/schemas/AutoModelResource")
     *               ),
     *               @OA\Property(property="current_page", type="integer"),
     *               @OA\Property(property="last_page", type="integer"),
     *               @OA\Property(property="per_page", type="integer"),
     *               @OA\Property(property="total", type="integer")
     *           )
     *       )
     *   ),
     *   @OA\Response(response=401, description="Не авторизован"),
     *   @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @return JsonResponse
     * @throws ServiceException
     */
    public function index(): JsonResponse
    {
        $models = $this->autoModelService->list();
        return ApiResponse::success(AutoModelResource::collection($models));
    }

    /**
     * Создать новую модель автомобиля
     *
     * @OA\Post(
     *     path="/auto_model",
     *     tags={"Auto Model"},
     *     summary="Создать новую модель автомобиля",
     *     description="Создает новую модель автомобиля с указанными данными",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для создания модели автомобиля",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoModelRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Модель автомобиля успешно создана",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoModelResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param AutoModelRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    public function store(AutoModelRequest $request): JsonResponse
    {
        $autoModelResponseDTO = $this->autoModelService->create($request->getDto());
        return ApiResponse::created(new AutoModelResource($autoModelResponseDTO));
    }

    /**
     * Получить модель автомобиля по ID
     *
     * @OA\Get(
     *     path="/auto_model/{id}",
     *     tags={"Auto Model"},
     *     summary="Получить модель автомобиля по ID",
     *     description="Возвращает данные модели автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID модели автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoModelResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Модель автомобиля не найдена"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @throws ServiceException|ResourceNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        $autoModelResponseDTO = $this->autoModelService->find($id);
        return ApiResponse::success(new AutoModelResource($autoModelResponseDTO));
    }

    /**
     * Обновить модель автомобиля
     *
     * @OA\Put(
     *     path="/auto_model/{id}",
     *     tags={"Auto Model"},
     *     summary="Обновить модель автомобиля",
     *     description="Обновляет данные модели автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID модели автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления модели автомобиля",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoModelRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Модель автомобиля успешно обновлена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoModelResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Модель автомобиля не найдена"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id
     * @param AutoModelRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    public function update(int $id, AutoModelRequest $request): JsonResponse
    {
        $autoModelResponseDTO = $this->autoModelService->update($id, $request->getDTO());
        return ApiResponse::success(new AutoModelResource($autoModelResponseDTO));
    }

    /**
     * Удалить модель автомобиля
     *
     * @OA\Delete(
     *     path="/auto_model/{id}",
     *     tags={"Auto Model"},
     *     summary="Удалить модель автомобиля",
     *     description="Удаляет модель автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID модели автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Модель автомобиля успешно удалена"
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Модель автомобиля не найдена"),
     *     @OA\Response(response=409, description="Конфликт - невозможно удалить модель с привязанными автомобилями"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->autoModelService->destroy($id);
        return ApiResponse::noContent();
    }

    protected function getRequestClass(): string
    {
        return AutoModelRequest::class;
    }
}
