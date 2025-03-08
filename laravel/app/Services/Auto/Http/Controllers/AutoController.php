<?php

namespace App\Services\Auto\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Auto\Http\Requests\AutoRequest;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * @package App\Services\Auto\Http\Controllers
 */
class AutoController extends Controller
{
    /**
     *
     * @param AutoServiceInterface $autoService
     */
    public function __construct(private readonly AutoServiceInterface $autoService)
    {
    }

    /**
     * @OA\Get(
     *   path="/auto",
     *   tags={"Auto"},
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(ref="#/components/schemas/UserLoginResource")
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
     * @param AutoRequest $request
     * @return JsonResource
     */
    public function store(AutoRequest $request): JsonResource
    {
        return $this->autoService->create($request->getDTO());
    }

    /**
     * @OA\Patch(
     *     path="/auto/{id}",
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
     * @param int $id
     * @param AutoRequest $request
     * @return JsonResource
     */
    public function update(int $id, AutoRequest $request): JsonResource
    {
        return $this->autoService->update($id, $request->getDTO());
    }

    /**
     * @OA\Delete(
     *     path="/auto/{id}",
     *     tags={"Auto"},
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
        $this->autoService->delete($id);
    }
}
