<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Auto;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use App\Services\Auto\DTO\Response\AutoResponseDTO;
use App\Services\Auto\Interfaces\AutoRepositoryInterface;
use App\Services\Auto\Models\Auto;
use App\Services\Auto\Services\AutoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Unit-тесты для AutoService.
 *
 * Проверяет бизнес-логику сервиса автомобилей с мокированными репозиториями.
 * Реальная база данных не используется.
 */
class AutoServiceTest extends TestCase
{
    /**
     * Мок репозитория автомобилей.
     *
     * @var MockInterface&AutoRepositoryInterface
     */
    private MockInterface $repository;

    /**
     * Тестируемый сервис автомобилей.
     *
     * @var AutoService
     */
    private AutoService $service;

    /**
     * Настройка тестового окружения перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(AutoRepositoryInterface::class);
        $this->service    = new AutoService($this->repository);

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
     * Метод list() должен вернуть пагинированный список автомобилей.
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
    // listByUserId()
    // =========================================================

    /**
     * Метод listByUserId() должен вернуть коллекцию DTO автомобилей пользователя.
     *
     * @return void
     */
    public function test_list_by_user_id_returns_collection_of_dto(): void
    {
        $auto = $this->makeAutoModel();

        $collection = new Collection([$auto]);

        $this->repository
            ->shouldReceive('findByUserId')
            ->once()
            ->with(1)
            ->andReturn($collection);

        $result = $this->service->listByUserId(1);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(AutoResponseDTO::class, $result->first());
        $this->assertSame(1, $result->first()->id);
    }

    /**
     * Метод listByUserId() должен вернуть пустую коллекцию если у пользователя нет автомобилей.
     *
     * @return void
     */
    public function test_list_by_user_id_returns_empty_collection_when_no_autos(): void
    {
        $this->repository
            ->shouldReceive('findByUserId')
            ->once()
            ->with(99)
            ->andReturn(new Collection([]));

        $result = $this->service->listByUserId(99);

        $this->assertCount(0, $result);
    }

    // =========================================================
    // listByCurrentUser()
    // =========================================================

    /**
     * Метод listByCurrentUser() должен вернуть пагинированный список автомобилей текущего пользователя.
     *
     * @return void
     */
    public function test_list_by_current_user_returns_paginator(): void
    {
        Auth::shouldReceive('id')
            ->once()
            ->andReturn(5);

        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $this->repository
            ->shouldReceive('paginateByUserId')
            ->once()
            ->with(5)
            ->andReturn($paginator);

        $result = $this->service->listByCurrentUser();

        $this->assertSame($paginator, $result);
    }

    // =========================================================
    // find()
    // =========================================================

    /**
     * Метод find() без проверки пользователя должен вернуть DTO по ID.
     *
     * @return void
     */
    public function test_find_returns_dto_without_user_check(): void
    {
        $auto = $this->makeAutoModel();

        Auth::shouldReceive('check')->never();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($auto);

        $result = $this->service->find(1, false);

        $this->assertInstanceOf(AutoResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
    }

    /**
     * Метод find() с проверкой пользователя и авторизованным пользователем использует findByIdForUser.
     *
     * @return void
     */
    public function test_find_with_user_check_when_authenticated_uses_find_by_id_for_user(): void
    {
        $auto = $this->makeAutoModel();

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('id')->once()->andReturn(3);

        $this->repository
            ->shouldReceive('findByIdForUser')
            ->once()
            ->with(1, 3)
            ->andReturn($auto);

        $result = $this->service->find(1, true);

        $this->assertInstanceOf(AutoResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
    }

    /**
     * Метод find() с проверкой пользователя и без авторизации использует find.
     *
     * @return void
     */
    public function test_find_with_user_check_when_not_authenticated_uses_find(): void
    {
        $auto = $this->makeAutoModel();

        Auth::shouldReceive('check')->once()->andReturn(false);

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($auto);

        $result = $this->service->find(1, true);

        $this->assertInstanceOf(AutoResponseDTO::class, $result);
    }

    /**
     * Метод find() должен выбросить ServiceException если автомобиль не найден.
     *
     * @return void
     */
    public function test_find_throws_service_exception_when_not_found(): void
    {
        Auth::shouldReceive('check')->never();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with(999)
            ->andReturn(null);

        $this->expectException(ServiceException::class);

        $this->service->find(999, false);
    }

    // =========================================================
    // create()
    // =========================================================

    /**
     * Метод create() должен создать автомобиль и вернуть DTO.
     *
     * @return void
     */
    public function test_create_returns_dto(): void
    {
        $dto  = $this->makeAutoRequestDto();
        $auto = $this->makeAutoModel();

        Auth::shouldReceive('id')->once()->andReturn(2);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->andReturn($auto);

        $result = $this->service->create($dto);

        $this->assertInstanceOf(AutoResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
    }

    // =========================================================
    // update()
    // =========================================================

    /**
     * Метод update() без авторизации должен обновить автомобиль и вернуть DTO.
     *
     * @return void
     */
    public function test_update_without_auth_returns_dto(): void
    {
        $dto  = $this->makeAutoRequestDto();
        $auto = $this->makeAutoModel();

        Auth::shouldReceive('check')->once()->andReturn(false);

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with(1, Mockery::type('array'))
            ->andReturn($auto);

        $result = $this->service->update(1, $dto);

        $this->assertInstanceOf(AutoResponseDTO::class, $result);
        $this->assertSame(1, $result->id);
    }

    /**
     * Метод update() с авторизацией должен использовать updateForUser.
     *
     * @return void
     */
    public function test_update_with_auth_uses_update_for_user(): void
    {
        $dto  = $this->makeAutoRequestDto();
        $auto = $this->makeAutoModel();

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('id')->once()->andReturn(7);

        $this->repository
            ->shouldReceive('updateForUser')
            ->once()
            ->with(1, 7, Mockery::type('array'))
            ->andReturn($auto);

        $result = $this->service->update(1, $dto);

        $this->assertInstanceOf(AutoResponseDTO::class, $result);
    }

    /**
     * Метод update() должен выбросить ServiceException если автомобиль не найден.
     *
     * @return void
     */
    public function test_update_throws_service_exception_when_not_found(): void
    {
        $dto = $this->makeAutoRequestDto();

        Auth::shouldReceive('check')->once()->andReturn(false);

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with(999, Mockery::type('array'))
            ->andReturn(null);

        $this->expectException(ServiceException::class);

        $this->service->update(999, $dto);
    }

    // =========================================================
    // destroy()
    // =========================================================

    /**
     * Метод destroy() без авторизации должен удалить автомобиль.
     *
     * @return void
     */
    public function test_destroy_without_auth_deletes_auto(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(false);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $this->service->destroy(1);

        // Проверяем что метод удаления был вызван (через Mockery assertion выше)
        $this->assertTrue(true);
    }

    /**
     * Метод destroy() с авторизацией должен использовать deleteForUser.
     *
     * @return void
     */
    public function test_destroy_with_auth_uses_delete_for_user(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('id')->once()->andReturn(4);

        $this->repository
            ->shouldReceive('deleteForUser')
            ->once()
            ->with(1, 4)
            ->andReturn(true);

        $this->service->destroy(1);

        $this->assertTrue(true);
    }

    /**
     * Метод destroy() должен выбросить ServiceException если автомобиль не найден.
     *
     * @return void
     */
    public function test_destroy_throws_service_exception_when_not_found(): void
    {
        Auth::shouldReceive('check')->once()->andReturn(false);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andReturn(false);

        $this->expectException(ServiceException::class);

        $this->service->destroy(999);
    }

    // =========================================================
    // Вспомогательные методы
    // =========================================================

    /**
     * Создаёт тестовую модель Auto с предустановленными значениями.
     *
     * @return Auto Мок-объект модели автомобиля.
     */
    private function makeAutoModel(): Auto
    {
        $auto = new Auto();
        $auto->forceFill([
            'id'            => 1,
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Чёрный',
            'auto_model_id' => 1,
            'auto_mark_id'  => 1,
            'user_id'       => 1,
        ]);

        return $auto;
    }

    /**
     * Создаёт тестовый DTO запроса автомобиля.
     *
     * @return AutoRequestDTO DTO для создания/обновления автомобиля.
     */
    private function makeAutoRequestDto(): AutoRequestDTO
    {
        return new AutoRequestDTO(
            year: 2020,
            mileage: 50000,
            color: 'Чёрный',
            auto_model_id: 1,
            auto_mark_id: 1,
        );
    }
}
