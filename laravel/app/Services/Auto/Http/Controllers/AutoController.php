<?php

declare(strict_types=1);

namespace App\Services\Auto\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Http\Responses\ApiResponse;
use App\Services\Auto\Http\Requests\AutoRequest;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\Auto\Resources\AutoResource;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления автомобилями.
 *
 * @package App\Services\Auto\Http\Controllers
 */
class AutoController
{
    /**
     * @param AutoServiceInterface $autoService Сервис для работы с автомобилями.
     */
    public function __construct(private readonly AutoServiceInterface $autoService)
    {
    }

    /**
     * Получить список автомобилей с пагинацией.
     *
     * @OA\Get(
     *     path="/auto",
     *     tags={"Auto"},
     *     summary="Получить список автомобилей",
     *     description="Возвращает пагинированный список всех автомобилей",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(ref="#/components/schemas/AutoResource")
     *                 ),
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
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
     * Создать новый автомобиль.
     *
     * @OA\Post(
     *     path="/auto",
     *     tags={"Auto"},
     *     summary="Создать новый автомобиль",
     *     description="Создаёт новый автомобиль с указанными данными",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для создания автомобиля",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Автомобиль успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param AutoRequest $request Запрос с данными для создания автомобиля.
     * @return JsonResponse
     * @throws ServiceException
     */
    public function store(AutoRequest $request): JsonResponse
    {
        $autoResponseDTO = $this->autoService->create($request->getDto());
        return ApiResponse::created(new AutoResource($autoResponseDTO));
    }

    /**
     * Получить автомобиль по идентификатору.
     *
     * @OA\Get(
     *     path="/auto/{id}",
     *     tags={"Auto"},
     *     summary="Получить автомобиль по ID",
     *     description="Возвращает данные автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Автомобиль не найден"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id Идентификатор автомобиля.
     * @return JsonResponse
     * @throws ServiceException|ResourceNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        $autoResponseDTO = $this->autoService->find($id);
        return ApiResponse::success(new AutoResource($autoResponseDTO));
    }

    /**
     * Обновить автомобиль по идентификатору.
     *
     * @OA\Put(
     *     path="/auto/{id}",
     *     tags={"Auto"},
     *     summary="Обновить автомобиль",
     *     description="Обновляет данные автомобиля по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления автомобиля",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Автомобиль успешно обновлён",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AutoResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Автомобиль не найден"),
     *     @OA\Response(response=422, description="Ошибка валидации"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int         $id      Идентификатор автомобиля.
     * @param AutoRequest $request Запрос с данными для обновления автомобиля.
     * @return JsonResponse
     * @throws ServiceException|ResourceNotFoundException
     */
    public function update(int $id, AutoRequest $request): JsonResponse
    {
        $autoResponseDTO = $this->autoService->update($id, $request->getDTO());
        return ApiResponse::success(new AutoResource($autoResponseDTO));
    }

    /**
     * Удалить автомобиль по идентификатору.
     *
     * @OA\Delete(
     *     path="/auto/{id}",
     *     tags={"Auto"},
     *     summary="Удалить автомобиль",
     *     description="Удаляет автомобиль по указанному ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID автомобиля",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Автомобиль успешно удалён"
     *     ),
     *     @OA\Response(response=401, description="Не авторизован"),
     *     @OA\Response(response=404, description="Автомобиль не найден"),
     *     @OA\Response(response=500, description="Внутренняя ошибка сервера")
     * )
     *
     * @param int $id Идентификатор автомобиля.
     * @return JsonResponse
     * @throws ResourceNotFoundException|ServiceException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->autoService->destroy($id);
        return ApiResponse::noContent();
    }
}
