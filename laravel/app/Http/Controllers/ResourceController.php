<?php

namespace App\Http\Controllers;

use App\Interfaces\DTOGetterInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Request;

/**
 * Абстракция с набором методов для ресурсных роутов
 *
 *
 * @package App\Http\Controllers
 */
abstract class ResourceController extends Controller
{
    /**
     * @return ResourceCollection
     */
    abstract public function index(): ResourceCollection;

    /**
     * @param Request $request
     * @return JsonResource
     */
    abstract public function store(Request $request): JsonResource;

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResource
     */
    abstract public function update(int $id, Request $request): JsonResource;

    /**
     * @param int $id
     * @return void
     */
    abstract public function destroy(int $id): void;

    /**
     * @return string
     */
    abstract protected function getRequestClass(): string;

    /**
     * @return mixed
     */
    protected function getDTO(): mixed
    {
        $requestClass = $this->getRequestClass();
        $formRequest = new $requestClass();

        if ($formRequest instanceof DTOGetterInterface) {
            return $formRequest->getDTO();
        }

        throw new \InvalidArgumentException('Request class must implement DTOGetterInterface');
    }
}
