<?php

namespace App\Services\Auto\Http\Controllers;

use App\Http\Controllers\ResourceController;
use App\Services\Auto\Services\AutoModelService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Request;

/**
 * @package App\Services\Auto\Http\Controllers
 */
class AutoModelController extends ResourceController
{
    /**
     * @param AutoModelService $autoModelService
     */
    public function __construct(private readonly AutoModelService $autoModelService)
    {
    }

    /**
     * @OA\Get(
     *   path="/auto_model",
     *   tags={"Auto"},
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *       @OA\JsonContent(
     *           type="array",
     *           @OA\Items(
     *               ref="#/components/schemas/AutoModelResource"
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
        return $this->autoModelService->list();
    }

    /**
     * @OA\Post(
     *     path="/auto_model",
     *     tags={"Auto"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoModelRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoModelResource")
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
        return $this->autoModelService->create($this->getDTO());
    }

    /**
     * @OA\Patch(
     *     path="/auto_model/{id}",
     *     tags={"Auto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of auto model",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoModelRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoModelResource")
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
        return $this->autoModelService->update($id, $this->getDTO());
    }

    /**
     * @OA\Delete(
     *     path="/auto_model/{id}",
     *     tags={"Auto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of auto model",
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
        $this->autoModelService->destroy($id);
    }

    /**
     * @return string
     */
    protected function getRequestClass(): string
    {
        return AutoModelRequest::class;
    }
}
