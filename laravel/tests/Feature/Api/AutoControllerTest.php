<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Services\Auto\Models\Auto;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Models\AutoModel;
use App\Services\User\Models\User;
use Tests\TestCase;

/**
 * Feature-тесты для контроллера автомобилей.
 *
 * Проверяет все API endpoints модуля Auto:
 * - GET  /api/v1/auto        — список автомобилей
 * - POST /api/v1/auto        — создание автомобиля
 * - PUT  /api/v1/auto/{id}   — обновление автомобиля
 * - DELETE /api/v1/auto/{id} — удаление автомобиля
 */
class AutoControllerTest extends TestCase
{
    /**
     * Базовый URL для endpoints автомобилей.
     */
    private const BASE_URL = '/api/v1/auto';

    /**
     * Создаёт пользователя и аутентифицирует его через Sanctum перед каждым тестом.
     *
     * @return void
     */
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    // -----------------------------------------------------------------------
    // GET /api/v1/auto
    // -----------------------------------------------------------------------

    /**
     * Тест: список автомобилей возвращает HTTP 200 и корректную структуру JSON.
     *
     * @return void
     */
    public function test_index_returns_200_with_paginated_list(): void
    {
        Auto::factory()->count(3)->create();

        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'year',
                        'mileage',
                        'color',
                    ],
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
     * Тест: список автомобилей возвращает пустой data при отсутствии записей.
     *
     * @return void
     */
    public function test_index_returns_empty_data_when_no_autos_exist(): void
    {
        $response = $this->getJson(self::BASE_URL);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);
    }

    // -----------------------------------------------------------------------
    // POST /api/v1/auto
    // -----------------------------------------------------------------------

    /**
     * Тест: создание автомобиля с корректными данными возвращает HTTP 201.
     *
     * @return void
     */
    public function test_store_creates_auto_and_returns_201(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $payload = [
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Black',
            'auto_model_id' => $model->id,
            'auto_mark_id'  => $mark->id,
        ];

        $response = $this->postJson(self::BASE_URL, $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'year',
                    'mileage',
                    'color',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'year'    => 2020,
                    'mileage' => 50000,
                    'color'   => 'Black',
                ],
            ]);

        $this->assertDatabaseHas('autos', [
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Black',
            'auto_model_id' => $model->id,
            'auto_mark_id'  => $mark->id,
        ]);
    }

    /**
     * Тест: создание автомобиля без обязательных полей возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_required_fields_are_missing(): void
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
     * Тест: создание автомобиля с несуществующими auto_model_id и auto_mark_id возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_model_or_mark_does_not_exist(): void
    {
        $payload = [
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Red',
            'auto_model_id' => 99999,
            'auto_mark_id'  => 99999,
        ];

        $response = $this->postJson(self::BASE_URL, $payload);

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
     * Тест: создание автомобиля с годом выпуска меньше 1900 возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_year_is_below_minimum(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $payload = [
            'year'          => 1800,
            'mileage'       => 10000,
            'color'         => 'White',
            'auto_model_id' => $model->id,
            'auto_mark_id'  => $mark->id,
        ];

        $response = $this->postJson(self::BASE_URL, $payload);

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
     * Тест: создание автомобиля с цветом, содержащим недопустимые символы, возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_store_returns_error_when_color_contains_invalid_characters(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $payload = [
            'year'          => 2020,
            'mileage'       => 10000,
            'color'         => 'Чёрный',
            'auto_model_id' => $model->id,
            'auto_mark_id'  => $mark->id,
        ];

        $response = $this->postJson(self::BASE_URL, $payload);

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
    // PUT /api/v1/auto/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: обновление существующего автомобиля возвращает HTTP 200 и обновлённые данные.
     *
     * @return void
     */
    public function test_update_returns_200_with_updated_data(): void
    {
        /** @var Auto $auto */
        $auto = Auto::factory()->create([
            'user_id' => $this->user->id,
            'year'    => 2015,
            'mileage' => 30000,
            'color'   => 'Blue',
        ]);

        /** @var AutoMark $newMark */
        $newMark = AutoMark::factory()->create();

        /** @var AutoModel $newModel */
        $newModel = AutoModel::factory()->create();

        $payload = [
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Red',
            'auto_model_id' => $newModel->id,
            'auto_mark_id'  => $newMark->id,
        ];

        $response = $this->putJson(self::BASE_URL . '/' . $auto->id, $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'year',
                    'mileage',
                    'color',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id'      => $auto->id,
                    'year'    => 2020,
                    'mileage' => 50000,
                    'color'   => 'Red',
                ],
            ]);

        $this->assertDatabaseHas('autos', [
            'id'      => $auto->id,
            'year'    => 2020,
            'mileage' => 50000,
            'color'   => 'Red',
        ]);
    }

    /**
     * Тест: обновление несуществующего автомобиля возвращает HTTP 404.
     *
     * @return void
     */
    public function test_update_returns_404_when_auto_not_found(): void
    {
        /** @var AutoMark $mark */
        $mark = AutoMark::factory()->create();

        /** @var AutoModel $model */
        $model = AutoModel::factory()->create();

        $payload = [
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Green',
            'auto_model_id' => $model->id,
            'auto_mark_id'  => $mark->id,
        ];

        $response = $this->putJson(self::BASE_URL . '/99999', $payload);

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
     * Тест: обновление автомобиля без обязательных полей возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_update_returns_error_when_required_fields_are_missing(): void
    {
        /** @var Auto $auto */
        $auto = Auto::factory()->create();

        $response = $this->putJson(self::BASE_URL . '/' . $auto->id, []);

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
     * Тест: обновление автомобиля с несуществующими auto_model_id и auto_mark_id возвращает ошибку валидации 422.
     *
     * @return void
     */
    public function test_update_returns_error_when_model_or_mark_does_not_exist(): void
    {
        /** @var Auto $auto */
        $auto = Auto::factory()->create();

        $payload = [
            'year'          => 2020,
            'mileage'       => 50000,
            'color'         => 'Black',
            'auto_model_id' => 99999,
            'auto_mark_id'  => 99999,
        ];

        $response = $this->putJson(self::BASE_URL . '/' . $auto->id, $payload);

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
    // DELETE /api/v1/auto/{id}
    // -----------------------------------------------------------------------

    /**
     * Тест: удаление существующего автомобиля возвращает HTTP 204.
     *
     * @return void
     */
    public function test_destroy_returns_204_when_auto_deleted(): void
    {
        /** @var Auto $auto */
        $auto = Auto::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(self::BASE_URL . '/' . $auto->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('autos', ['id' => $auto->id]);
    }

    /**
     * Тест: удаление несуществующего автомобиля возвращает HTTP 404.
     *
     * @return void
     */
    public function test_destroy_returns_404_when_auto_not_found(): void
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
}
