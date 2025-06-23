<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidDtoRequestException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Абстракция с набором методов для ресурсных роутов
 *
 * @package App\Http\Controllers
 */
abstract class ResourceController extends Controller
{
    /**
     * @return JsonResponse
     */
    abstract public function index(): JsonResponse;

    /**
     * @param FormRequest $request
     * @return JsonResponse
     */
    abstract public function store(FormRequest $request): JsonResponse;

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidDtoRequestException
     */
    abstract public function update(int $id, Request $request): JsonResponse;

    /**
     * @param int $id
     * @return JsonResponse
     */
    abstract public function destroy(int $id): JsonResponse;
}
