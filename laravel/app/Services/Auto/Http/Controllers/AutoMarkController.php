<?php

namespace App\Services\Auto\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Http\Responses\ApiResponse;
use App\Services\Auto\Http\Requests\AutoMarkRequest;
use App\Services\Auto\Resources\AutoMarkResource;
use App\Services\Auto\Services\AutoMarkService;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления марками автомобилей
 *
 * @package App\Services\Auto\Http\Controllers
 */
class AutoMarkController
{
    public function __construct(private readonly AutoMarkService $autoMarkService)
    {
    }

    /**
     * Получить список марок автомобилей с пагинацией
     *
     * @OA\Get(
     *   path="/auto_mark",
     *   tags={"Auto Mark"},
     *   summary="Получить список марок автомобилей",
     *   description="Возвращает пагинированный список всех марок автомобилей",
     *   @OA\Response(
     *       response=200,
     *       description="Успешный ответ",
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="success", type="boolean", example=true),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="data", type="array",
     *                   @OA\Items(ref="#/components/schemas/AutoMarkResource")
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
        $marks = $this->autoMarkService->list();
        return ApiResponse::success(AutoMarkResource::collection($marks));
    }

    /**
     * Создать новую марку автомобиля
     *
     * @OA\Post(
     *     path="/auto_mark",
     *     tags={"Auto Mark"},
     *     summary="Создать новую марку автомобиля",
     *     description="Создает новую марку автомобиля с указанными данными",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для создания марки автомобиля",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoMarkRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Марка автомобиля успешно создана",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoMarkResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param AutoMarkRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    public function store(AutoMarkRequest $request): JsonResponse
    {
        $autoMarkResponseDTO = $this->autoMarkService->create($request->getDto());
        return ApiResponse::created(new AutoMarkResource($autoMarkResponseDTO));
    }

    /**
     * Получить марку автомобиля по ID
     *
     * @OA\Get(
     *     path="/auto_mark/{id}",
     *     tags={"Auto Mark"},
     *     summary="Получить марку автомобиля по ID",
     *     description="Возвращает данные марки автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID марки автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoMarkResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Марка автомобиля не найдена"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @throws ServiceException|ResourceNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        $autoMarkResponseDTO = $this->autoMarkService->find($id);
        return ApiResponse::success(new AutoMarkResource($autoMarkResponseDTO));
    }

    /**
     * Обновить марку автомобиля
     *
     * @OA\Put(
     *     path="/auto_mark/{id}",
     *     tags={"Auto Mark"},
     *     summary="Обновить марку автомобиля",
     *     description="Обновляет данные марки автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID марки автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления марки автомобиля",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoMarkRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Марка автомобиля успешно обновлена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoMarkResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Марка автомобиля не найдена"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id
     * @param AutoMarkRequest $request
     * @return JsonResponse
     * @throws ServiceException
     */
    public function update(int $id, AutoMarkRequest $request): JsonResponse
    {
        $autoMarkResponseDTO = $this->autoMarkService->update($id, $request->getDTO());
        return ApiResponse::success(new AutoMarkResource($autoMarkResponseDTO));
    }

    /**
     * Удалить марку автомобиля
     *
     * @OA\Delete(
     *     path="/auto_mark/{id}",
     *     tags={"Auto Mark"},
     *     summary="Удалить марку автомобиля",
     *     description="Удаляет марку автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID марки автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Марка автомобиля успешно удалена"
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Марка автомобиля не найдена"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id
     * @return JsonResponse
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->autoMarkService->destroy($id);
        return ApiResponse::noContent();
    }

    protected function getRequestClass(): string
    {
        return AutoMarkRequest::class;
    }
}
