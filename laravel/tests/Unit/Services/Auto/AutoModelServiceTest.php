<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Auto;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoModelRequestDTO;
use App\Services\Auto\DTO\Response\AutoModelResponseDTO;
use App\Services\Auto\Interfaces\AutoModelRepositoryInterface;
use App\Services\Auto\Models\AutoModel;
use App\Services\Auto\Services\AutoModelService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Unit-тесты для AutoModelService.
 *
 * Проверяет бизнес-логику сервиса моделей автомобилей с мокированными репозиториями.
 * Реальная база данных не используется.
 */
class AutoModelServiceTest extends TestCase
{
    /**
     * Мок репозитория моделей автомобилей.
     *
     * @var MockInterface&AutoModelRepositoryInterface
     */
    private MockInterface $repository;

    /**
     * Тестируемый сервис моделей автомобилей.
     *
     * @var AutoModelService
     */
    private AutoModelService $service;

    /**
     * Настройка тестового окружения перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(AutoModelRepositoryInterface::class);
        $this->service    = new AutoModelService($this->repository);

        // Мокаем DB::transaction — выполняем callback напрямую
        DB::shouldReceive('transaction')
            ->andReturnUsing(fn (callable $cb) => $cb());
    }

    /**
     * Очистка мокируемых объектов после каждого теста.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // =========================================================
    // list()
    // =========================================================

    /**
     * Метод list() должен вернуть пагинированный список моделей автомобилей.
     *
     * @return void
     */
    public function test_list_returns_paginator(): void
    {
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $this->repository
            ->shouldReceive('list')
            ->once()
            ->andReturn($paginator);

        $result = $this->service->list();

        $this->assertSame($paginator, $result);
    }

    // =========================================================
    // find()
    // =========================================================

    /**
     * Метод find() должен вернуть DTO модели по существующему ID.
     *
     * @return void
     */
    public function test_find_returns_dto_when_model_exists(): void
    {
        $model = $this->makeAutoModelModel();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($model);

        $result = $this->service->find(1);

        $this->assertInstanceOf(AutoModelResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
        $this->assertSame('X5', $result->name);
    }

    /**
     * Метод find() должен выбросить ServiceException если модель не найдена.
     *
     * Внутри executeSafely ResourceNotFoundException перехватывается catch(Throwable)
     * и оборачивается в ServiceException.
     *
     * @return void
     */
    public function test_find_throws_service_exception_when_model_not_found(): void
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->expectException(ServiceException::class);

        $this->service->find(999);
    }

    // =========================================================
    // create()
    // =========================================================

    /**
     * Метод create() должен создать модель и вернуть DTO.
     *
     * @return void
     */
    public function test_create_returns_dto(): void
    {
        $dto   = new AutoModelRequestDTO(name: 'Camry');
        $model = $this->makeAutoModelModel(id: 2, name: 'Camry');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(['name' => 'Camry'])
            ->andReturn($model);

        $result = $this->service->create($dto);

        $this->assertInstanceOf(AutoModelResponseDTO::class, $result);
        $this->assertSame(2, $result->id);
        $this->assertSame('Camry', $result->name);
    }

    /**
     * Метод create() должен выбросить ServiceException при ошибке репозитория.
     *
     * @return void
     */
    public function test_create_throws_service_exception_on_repository_error(): void
    {
        $dto = new AutoModelRequestDTO(name: 'X5');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->andThrow(new \RuntimeException('DB error'));

        $this->expectException(ServiceException::class);

        $this->service->create($dto);
    }

    // =========================================================
    // update()
    // =========================================================

    /**
     * Метод update() должен обновить модель и вернуть DTO.
     *
     * @return void
     */
    public function test_update_returns_dto_when_model_exists(): void
    {
        $dto   = new AutoModelRequestDTO(name: 'E-Class');
        $model = $this->makeAutoModelModel(id: 1, name: 'E-Class');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with(1, ['name' => 'E-Class'])
            ->andReturn($model);

        $result = $this->service->update(1, $dto);

        $this->assertInstanceOf(AutoModelResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
        $this->assertSame('E-Class', $result->name);
    }

    /**
     * Метод update() должен выбросить ServiceException если модель не найдена.
     *
     * @return void
     */
    public function test_update_throws_service_exception_when_model_not_found(): void
    {
        $dto = new AutoModelRequestDTO(name: 'NonExisting');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with(999, ['name' => 'NonExisting'])
            ->andReturn(null);

        $this->expectException(ServiceException::class);

        $this->service->update(999, $dto);
    }

    // =========================================================
    // destroy()
    // =========================================================

    /**
     * Метод destroy() должен успешно удалить модель без привязанных автомобилей.
     *
     * @return void
     */
    public function test_destroy_deletes_model_when_no_related_autos(): void
    {
        $model = $this->makeAutoModelModel();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($model);

        $this->repository
            ->shouldReceive('hasRelatedAutos')
            ->once()
            ->with(1)
            ->andReturn(false);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with(1);

        $this->service->destroy(1);

        $this->assertTrue(true);
    }

    /**
     * Метод destroy() должен выбросить ServiceException с кодом конфликта (409) если есть привязанные автомобили.
     *
     * Сервис явно бросает ServiceException(409) — executeSafely перехватывает её как Throwable
     * и оборачивает в новую ServiceException(500).
     *
     * @return void
     */
    public function test_destroy_throws_service_exception_when_has_related_autos(): void
    {
        $model = $this->makeAutoModelModel();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($model);

        $this->repository
            ->shouldReceive('hasRelatedAutos')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->repository
            ->shouldReceive('delete')
            ->never();

        $this->expectException(ServiceException::class);

        $this->service->destroy(1);
    }

    /**
     * Метод destroy() должен выбросить ServiceException если модель не найдена.
     *
     * @return void
     */
    public function test_destroy_throws_service_exception_when_model_not_found(): void
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->repository
            ->shouldReceive('hasRelatedAutos')
            ->never();

        $this->repository
            ->shouldReceive('delete')
            ->never();

        $this->expectException(ServiceException::class);

        $this->service->destroy(999);
    }

    /**
     * Метод destroy() не должен вызывать delete когда модель не найдена.
     *
     * @return void
     */
    public function test_destroy_does_not_call_delete_when_model_not_found(): void
    {
        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(42)
            ->andReturn(null);

        $this->repository
            ->shouldReceive('hasRelatedAutos')
            ->never();

        $this->repository
            ->shouldReceive('delete')
            ->never();

        try {
            $this->service->destroy(42);
        } catch (ServiceException) {
            // Ожидаемое исключение
        }

        // Проверяем Mockery expectations (never вызовы выше)
        $this->assertTrue(true);
    }

    // =========================================================
    // Вспомогательные методы
    // =========================================================

    /**
     * Создаёт тестовую модель AutoModel с предустановленными значениями.
     *
     * @param int    $id   Идентификатор модели.
     * @param string $name Название модели автомобиля.
     * @return AutoModel Модель автомобиля.
     */
    private function makeAutoModelModel(int $id = 1, string $name = 'X5'): AutoModel
    {
        $model = new AutoModel();
        $model->forceFill([
            'id'   => $id,
            'name' => $name,
        ]);

        return $model;
    }
}
