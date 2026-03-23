<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Services\Auto\Models\Auto;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Models\AutoModel;
use App\Services\User\Models\User;
use Tests\TestCase;

/**
 * Feature-тесты для контроллера моделей автомобилей.
 *
 * Проверяет все API endpoints модуля AutoModel:
 * - GET  /api/v1/models        — список моделей
 * - POST /api/v1/models        — создание модели
 * - GET  /api/v1/models/{id}   — получение модели
 * - PUT  /api/v1/models/{id}   — обновление модели
 * - DELETE /api/v1/models/{id} — удаление модели
 */
class AutoModelControllerTest extends TestCase
{
    /**
     * Базовый URL для endpoints моделей автомобилей.
     */
    private const BASE_URL = '/api/v1/models';

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
    // GET /api/v1/models
    // -----------------------------------------------------------------------

    /**
     * Тест: список моделей возвращает HTTP 200 и корректную структуру JSON.
     *
     * @return void
     */
    public function test_index_returns_200_with_paginated_list(): void
    {
        AutoModel::factory()->count(3)->create();

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
     * Тест: список моделей возвращает пустой data при отсутствии записей.
     *
     * @return void
     */
    public function test_index_returns_empty_data_when_no_models_exist(): void
    {
        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);
    }

    // -----------------------------------------------------------------------
    // POST /api/v1/models
    // -----------------------------------------------------------------------

    /**
     * Тест: создание модели возвращает HTTP 201 и данные созданной модели.
     *
     * @return void
     */
    public function test_store_creates_model_and_returns_201(): void
    {
        $payload = ['name' => 'Camry'];

        $response = $this->postJson(self::BASE_URL, $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => ['name' => 'Camry'],
            ]);

        $this->assertDatabaseHas('auto_models', ['name' => 'Camry']);
    }

    /**
     * Тест: создание модели без обязательного поля name возвращает ошибку валидации 422.
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
     * Тест: создание модели с пустым name возвращает ошибку валидации 422.
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
     * Тест: создание модели с name длиннее 255 символов возвращает ошибку валидации 422.
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
    // GET /api/v1/models/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: получение существующей модели возвращает HTTP 200 и данные.
     *
     * @return void
     */
    public function test_show_returns_200_with_model_data(): void
    {
        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $response = $this->getJson(self::BASE_URL . '/' . $model->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id'   => $model->id,
                    'name' => $model->name,
                ],
            ]);
    }

    /**
     * Тест: получение несуществующей модели возвращает HTTP 404.
     *
     * @return void
     */
    public function test_show_returns_404_when_model_not_found(): void
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
    // PUT /api/v1/models/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: обновление существующей модели возвращает HTTP 200 и обновлённые данные.
     *
     * @return void
     */
    public function test_update_returns_200_with_updated_data(): void
    {
        /** @var AutoModel $model */
        $model = AutoModel::factory()->create(['name' => 'OldModel']);

        $response = $this->putJson(self::BASE_URL . '/' . $model->id, ['name' => 'NewModel']);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name'],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id'   => $model->id,
                    'name' => 'NewModel',
                ],
            ]);

        $this->assertDatabaseHas('auto_models', ['id' => $model->id, 'name' => 'NewModel']);
    }

    /**
     * Тест: обновление несуществующей модели возвращает HTTP 404.
     *
     * @return void
     */
    public function test_update_returns_404_when_model_not_found(): void
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
     * Тест: обновление модели без обязательного поля name возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_update_returns_error_when_name_is_missing(): void
    {
        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $response = $this->putJson(self::BASE_URL . '/' . $model->id, []);

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
     * Тест: обновление модели с name длиннее 255 символов возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_update_returns_error_when_name_exceeds_max_length(): void
    {
        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $response = $this->putJson(self::BASE_URL . '/' . $model->id, ['name' => str_repeat('C', 256)]);

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
    // DELETE /api/v1/models/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: удаление существующей модели без привязанных авто возвращает HTTP 204.
     *
     * @return void
     */
    public function test_destroy_returns_204_when_model_deleted(): void
    {
        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $response = $this->deleteJson(self::BASE_URL . '/' . $model->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('auto_models', ['id' => $model->id]);
    }

    /**
     * Тест: удаление несуществующей модели возвращает HTTP 404.
     *
     * @return void
     */
    public function test_destroy_returns_404_when_model_not_found(): void
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
     * Тест: удаление модели с привязанными автомобилями возвращает HTTP 409 (конфликт).
     *
     * @return void
     */
    public function test_destroy_returns_409_when_model_has_related_autos(): void
    {
        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        Auto::factory()->create([
            'auto_model_id' => $model->id,
            'auto_mark_id'  => $mark->id,
        ]);

        $response = $this->deleteJson(self::BASE_URL . '/' . $model->id);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonStructure([
                'success',
                'message',
            ]);

        $this->assertDatabaseHas('auto_models', ['id' => $model->id]);
    }
}
