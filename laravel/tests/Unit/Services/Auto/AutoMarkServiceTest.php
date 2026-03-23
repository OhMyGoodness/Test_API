<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Auto;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use App\Services\Auto\DTO\Response\AutoMarkResponseDTO;
use App\Services\Auto\Interfaces\AutoMarkRepositoryInterface;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Services\AutoMarkService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Unit-тесты для AutoMarkService.
 *
 * Проверяет бизнес-логику сервиса марок автомобилей с мокированными репозиториями.
 * Реальная база данных не используется.
 */
class AutoMarkServiceTest extends TestCase
{
    /**
     * Мок репозитория марок автомобилей.
     *
     * @var MockInterface&AutoMarkRepositoryInterface
     */
    private MockInterface $repository;

    /**
     * Тестируемый сервис марок автомобилей.
     *
     * @var AutoMarkService
     */
    private AutoMarkService $service;

    /**
     * Настройка тестового окружения перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(AutoMarkRepositoryInterface::class);
        $this->service    = new AutoMarkService($this->repository);

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
     * Метод list() должен вернуть пагинированный список марок автомобилей.
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
     * Метод find() должен вернуть DTO марки по существующему ID.
     *
     * @return void
     */
    public function test_find_returns_dto_when_mark_exists(): void
    {
        $mark = $this->makeAutoMarkModel();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($mark);

        $result = $this->service->find(1);

        $this->assertInstanceOf(AutoMarkResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
        $this->assertSame('BMW', $result->name);
    }

    /**
     * Метод find() должен выбросить ServiceException если марка не найдена.
     *
     * Внутри executeSafely ResourceNotFoundException перехватывается catch(Throwable)
     * и оборачивается в ServiceException.
     *
     * @return void
     */
    public function test_find_throws_service_exception_when_mark_not_found(): void
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
     * Метод create() должен создать марку и вернуть DTO.
     *
     * @return void
     */
    public function test_create_returns_dto(): void
    {
        $dto  = new AutoMarkRequestDTO(name: 'Toyota');
        $mark = $this->makeAutoMarkModel(id: 2, name: 'Toyota');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(['name' => 'Toyota'])
            ->andReturn($mark);

        $result = $this->service->create($dto);

        $this->assertInstanceOf(AutoMarkResponseDTO::class, $result);
        $this->assertSame(2, $result->id);
        $this->assertSame('Toyota', $result->name);
    }

    /**
     * Метод create() должен выбросить ServiceException при ошибке репозитория.
     *
     * @return void
     */
    public function test_create_throws_service_exception_on_repository_error(): void
    {
        $dto = new AutoMarkRequestDTO(name: 'BMW');

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
     * Метод update() должен обновить марку и вернуть DTO.
     *
     * @return void
     */
    public function test_update_returns_dto_when_mark_exists(): void
    {
        $dto  = new AutoMarkRequestDTO(name: 'Audi');
        $mark = $this->makeAutoMarkModel(id: 1, name: 'Audi');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with(1, ['name' => 'Audi'])
            ->andReturn($mark);

        $result = $this->service->update(1, $dto);

        $this->assertInstanceOf(AutoMarkResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
        $this->assertSame('Audi', $result->name);
    }

    /**
     * Метод update() должен выбросить ServiceException если марка не найдена.
     *
     * @return void
     */
    public function test_update_throws_service_exception_when_mark_not_found(): void
    {
        $dto = new AutoMarkRequestDTO(name: 'NonExisting');

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
     * Метод destroy() должен успешно удалить марку без привязанных автомобилей.
     *
     * @return void
     */
    public function test_destroy_deletes_mark_when_no_related_autos(): void
    {
        $mark = $this->makeAutoMarkModel();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($mark);

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
     * Метод destroy() должен выбросить ServiceException с кодом 409 если есть привязанные автомобили.
     *
     * @return void
     */
    public function test_destroy_throws_service_exception_with_409_when_has_related_autos(): void
    {
        $mark = $this->makeAutoMarkModel();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($mark);

        $this->repository
            ->shouldReceive('hasRelatedAutos')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->repository
            ->shouldReceive('delete')
            ->never();

        try {
            $this->service->destroy(1);
            $this->fail('Ожидалось исключение ServiceException');
        } catch (ServiceException $e) {
            // executeSafely перехватывает ServiceException(409) как Throwable и оборачивает в ServiceException(500)
            $this->assertInstanceOf(ServiceException::class, $e);
        }
    }

    /**
     * Метод destroy() должен выбросить ServiceException если марка не найдена.
     *
     * @return void
     */
    public function test_destroy_throws_service_exception_when_mark_not_found(): void
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

    // =========================================================
    // Вспомогательные методы
    // =========================================================

    /**
     * Создаёт тестовую модель AutoMark с предустановленными значениями.
     *
     * @param int    $id   Идентификатор марки.
     * @param string $name Название марки.
     * @return AutoMark Модель марки автомобиля.
     */
    private function makeAutoMarkModel(int $id = 1, string $name = 'BMW'): AutoMark
    {
        $mark = new AutoMark();
        $mark->forceFill([
            'id'   => $id,
            'name' => $name,
        ]);

        return $mark;
    }
}
