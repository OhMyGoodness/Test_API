<?php

namespace App\Services\Auto\Http\Controllers;

use App\Http\Controllers\ResourceController;
use App\Services\Auto\Http\Requests\AutoMarkRequest;
use App\Services\Auto\Services\AutoMarkService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Request;

/**
 * @package App\Services\Auto\Http\Controllers
 */
class AutoMarkController extends ResourceController
{
    /**
     * @param AutoMarkService $autoMarkService
     */
    public function __construct(private readonly AutoMarkService $autoMarkService) // нет нужды в этом кейсе делать сервис через интерфейс (в демо проекте)
    {
    }

    /**
     * @OA\Get(
     *   path="/auto_mark",
     *   tags={"Auto"},
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *       @OA\JsonContent(
     *           type="array",
     *           @OA\Items(
     *               ref="#/components/schemas/AutoMarkResource"
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
        return $this->autoMarkService->list();
    }

    /**
     * @OA\Post(
     *     path="/auto_mark",
     *     tags={"Auto"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoMarkRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoMarkResource")
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
        return $this->autoMarkService->create($this->getDTO());
    }

    /**
     * @OA\Patch(
     *     path="/auto_mark/{id}",
     *     tags={"Auto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of auto mark",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoMarkRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AutoMarkResource")
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
        return $this->autoMarkService->update($id, $this->getDTO());
    }

    /**
     * @OA\Delete(
     *     path="/auto_mark/{id}",
     *     tags={"Auto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of auto mark",
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
        $this->autoMarkService->destroy($id);
    }

    /**
     * @return string
     */
    protected function getRequestClass(): string
    {
        return AutoMarkRequest::class;
    }
}
