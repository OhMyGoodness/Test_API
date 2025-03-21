<?php

namespace App\Services\Auto\Http\Controllers;

use App\Http\Controllers\ResourceController;
use App\Services\Auto\Http\Requests\AutoRequest;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * @package App\Services\Auto\Http\Controllers
 */
class AutoController extends ResourceController
{
    /**
     *
     * @param AutoServiceInterface $autoService
     */
    public function __construct(private readonly AutoServiceInterface $autoService)
    {
    }

    /**
     * @return string
     */
    public function getRequestClass(): string
    {
        return AutoRequest::class;
    }

    /**
     * @OA\Get(
     *   path="/auto",
     *   tags={"Auto"},
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *       @OA\JsonContent(
     *           type="array",
     *           @OA\Items(
     *               ref="#/components/schemas/AutoResource"
     *           )
     *       )
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found")
     * )
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $userId = Auth::user()->id ?? null;

        return $userId ? $this->autoService->listByUserId($userId) : $this->autoService->list();
    }

    /**
     * @OA\Post(
     *     path="/auto",
     *     tags={"Auto"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        return $this->autoService->create($this->getDTO());
    }

    /**
     * @OA\Patch(
     *     path="/auto/{id}",
     *     tags={"Auto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of auto",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoResource")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     * @param int $id
     * @param Request $request
     * @return JsonResource
     */
    public function update(int $id, Request $request): JsonResource
    {
        return $this->autoService->update($id, $this->getDTO());
    }

    /**
     * @OA\Delete(
     *     path="/auto/{id}",
     *     tags={"Auto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of auto",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Schema(type="integer"),
     *     @OA\Response(response=201, description="Ok"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $this->autoService->destroy($id);
    }
}
