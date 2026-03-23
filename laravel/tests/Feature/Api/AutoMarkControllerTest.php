<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Services\Auto\Models\Auto;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Models\AutoModel;
use App\Services\User\Models\User;
use Tests\TestCase;

/**
 * Feature-тесты для контроллера марок автомобилей.
 *
 * Проверяет все API endpoints модуля AutoMark:
 * - GET  /api/v1/marks        — список марок
 * - POST /api/v1/marks        — создание марки
 * - GET  /api/v1/marks/{id}   — получение марки
 * - PUT  /api/v1/marks/{id}   — обновление марки
 * - DELETE /api/v1/marks/{id} — удаление марки
 */
class AutoMarkControllerTest extends TestCase
{
    /**
     * Базовый URL для endpoints марок автомобилей.
     */
    private const BASE_URL = '/api/v1/marks';

    /**
     * Создаёт пользователя и аутентифицирует его через Sanctum перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'sanctum');
    }

    // -----------------------------------------------------------------------
    // GET /api/v1/marks
    // -----------------------------------------------------------------------

    /**
     * Тест: список марок возвращает HTTP 200 и корректную структуру JSON.
     *
     * @return void
     */
    public function test_index_returns_200_with_paginated_list(): void
    {
        AutoMark::factory()->count(3)->create();

        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]);
    }

    /**
     * Тест: список марок возвращает пустой data при отсутствии записей.
     *
     * @return void
     */
    public function test_index_returns_empty_data_when_no_marks_exist(): void
    {
        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);
    }

    // -----------------------------------------------------------------------
    // POST /api/v1/marks
    // -----------------------------------------------------------------------

    /**
     * Тест: создание марки возвращает HTTP 201 и данные созданной марки.
     *
     * @return void
     */
    public function test_store_creates_mark_and_returns_201(): void
    {
        $payload = ['name' => 'Toyota'];

        $response = $this->postJson(self::BASE_URL, $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => ['name' => 'Toyota'],
            ]);

        $this->assertDatabaseHas('auto_marks', ['name' => 'Toyota']);
    }

    /**
     * Тест: создание марки без обязательного поля name возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_name_is_missing(): void
    {
        $response = $this->postJson(self::BASE_URL, []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    /**
     * Тест: создание марки с пустым name возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_name_is_empty_string(): void
    {
        $response = $this->postJson(self::BASE_URL, ['name' => '']);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    /**
     * Тест: создание марки с name длиннее 255 символов возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_name_exceeds_max_length(): void
    {
        $response = $this->postJson(self::BASE_URL, ['name' => str_repeat('A', 256)]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    // -----------------------------------------------------------------------
    // GET /api/v1/marks/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: получение существующей марки возвращает HTTP 200 и данные.
     *
     * @return void
     */
    public function test_show_returns_200_with_mark_data(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        $response = $this->getJson(self::BASE_URL . '/' . $mark->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id'   => $mark->id,
                    'name' => $mark->name,
                ],
            ]);
    }

    /**
     * Тест: получение несуществующей марки возвращает HTTP 404.
     *
     * @return void
     */
    public function test_show_returns_404_when_mark_not_found(): void
    {
        $response = $this->getJson(self::BASE_URL . '/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
            ]);
    }

    // -----------------------------------------------------------------------
    // PUT /api/v1/marks/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: обновление существующей марки возвращает HTTP 200 и обновлённые данные.
     *
     * @return void
     */
    public function test_update_returns_200_with_updated_data(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create(['name' => 'OldName']);

        $response = $this->putJson(self::BASE_URL . '/' . $mark->id, ['name' => 'NewName']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id'   => $mark->id,
                    'name' => 'NewName',
                ],
            ]);

        $this->assertDatabaseHas('auto_marks', ['id' => $mark->id, 'name' => 'NewName']);
    }

    /**
     * Тест: обновление несуществующей марки возвращает HTTP 404.
     *
     * @return void
     */
    public function test_update_returns_404_when_mark_not_found(): void
    {
        $response = $this->putJson(self::BASE_URL . '/99999', ['name' => 'SomeName']);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
            ]);
    }

    /**
     * Тест: обновление марки без обязательного поля name возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_update_returns_error_when_name_is_missing(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        $response = $this->putJson(self::BASE_URL . '/' . $mark->id, []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    /**
     * Тест: обновление марки с name длиннее 255 символов возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_update_returns_error_when_name_exceeds_max_length(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        $response = $this->putJson(self::BASE_URL . '/' . $mark->id, ['name' => str_repeat('B', 256)]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    // -----------------------------------------------------------------------
    // DELETE /api/v1/marks/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: удаление существующей марки без привязанных авто возвращает HTTP 204.
     *
     * @return void
     */
    public function test_destroy_returns_204_when_mark_deleted(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        $response = $this->deleteJson(self::BASE_URL . '/' . $mark->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('auto_marks', ['id' => $mark->id]);
    }

    /**
     * Тест: удаление несуществующей марки возвращает HTTP 404.
     *
     * @return void
     */
    public function test_destroy_returns_404_when_mark_not_found(): void
    {
        $response = $this->deleteJson(self::BASE_URL . '/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
            ]);
    }

    /**
     * Тест: удаление марки с привязанными автомобилями возвращает HTTP 409 (конфликт).
     *
     * @return void
     */
    public function test_destroy_returns_409_when_mark_has_related_autos(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        Auto::factory()->create([
            'auto_mark_id'  => $mark->id,
            'auto_model_id' => $model->id,
        ]);

        $response = $this->deleteJson(self::BASE_URL . '/' . $mark->id);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
            ]);

        $this->assertDatabaseHas('auto_marks', ['id' => $mark->id]);
    }
}
