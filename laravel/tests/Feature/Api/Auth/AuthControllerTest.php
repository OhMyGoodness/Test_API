<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use App\Services\User\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * Feature-тесты для эндпоинта авторизации POST /api/v1/auth/login.
 *
 * Проверяет корректную работу авторизации: успешный логин, ошибки аутентификации
 * и валидацию входящих данных.
 *
 * @package Tests\Feature\Api\Auth
 */
class AuthControllerTest extends TestCase
{
    /**
     * URL эндпоинта авторизации.
     */
    private const AUTH_LOGIN_URL = '/api/v1/auth/login';

    /**
     * Пароль, используемый при создании тестовых пользователей через фабрику.
     */
    private const DEFAULT_PASSWORD = 'password';

    /**
     * Успешная авторизация возвращает HTTP 200 и токен в теле ответа.
     *
     * @return void
     */
    public function test_successful_login_returns_200_with_token(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(self::AUTH_LOGIN_URL, [
            'email'    => $user->email,
            'password' => self::DEFAULT_PASSWORD,
        ]);

        $response->assertStatus(200)
                 ->assertJson(
                     fn(AssertableJson $json) => $json
                         ->where('success', true)
                         ->has('data.token')
                         ->whereType('data.token', 'string')
                 );
    }

    /**
     * Авторизация с верным email и неверным паролем возвращает HTTP 401.
     *
     * @return void
     */
    public function test_login_with_wrong_password_returns_401(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(self::AUTH_LOGIN_URL, [
            'email'    => $user->email,
            'password' => 'wrong_password_123',
        ]);

        $response->assertStatus(401)
                 ->assertJson(
                     fn(AssertableJson $json) => $json
                         ->where('success', false)
                         ->has('message')
                 );
    }

    /**
     * Авторизация с несуществующим email возвращает HTTP 401.
     *
     * @return void
     */
    public function test_login_with_nonexistent_email_returns_401(): void
    {
        $response = $this->postJson(self::AUTH_LOGIN_URL, [
            'email'    => 'nonexistent@example.com',
            'password' => self::DEFAULT_PASSWORD,
        ]);

        $response->assertStatus(401)
                 ->assertJson(
                     fn(AssertableJson $json) => $json
                         ->where('success', false)
                         ->has('message')
                 );
    }

    /**
     * Авторизация с пустым email возвращает ошибку валидации 422.
     *
     * Handler обрабатывает ValidationException как HTTP 422 с полями success, message, errors.
     *
     * @return void
     */
    public function test_login_with_empty_email_returns_error(): void
    {
        $response = $this->postJson(self::AUTH_LOGIN_URL, [
            'email'    => '',
            'password' => self::DEFAULT_PASSWORD,
        ]);

        $response->assertStatus(422)
                 ->assertJson(
                     fn(AssertableJson $json) => $json
                         ->where('success', false)
                         ->has('message')
                         ->has('errors')
                 );
    }

    /**
     * Авторизация с пустым паролем возвращает ошибку валидации 422.
     *
     * Handler обрабатывает ValidationException как HTTP 422 с полями success, message, errors.
     *
     * @return void
     */
    public function test_login_with_empty_password_returns_error(): void
    {
        $response = $this->postJson(self::AUTH_LOGIN_URL, [
            'email'    => 'user@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422)
                 ->assertJson(
                     fn(AssertableJson $json) => $json
                         ->where('success', false)
                         ->has('message')
                         ->has('errors')
                 );
    }

    /**
     * Тело успешного ответа содержит строго структуру {success: true, data: {token: "..."}}.
     *
     * @return void
     */
    public function test_successful_login_response_has_correct_structure(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(self::AUTH_LOGIN_URL, [
            'email'    => $user->email,
            'password' => self::DEFAULT_PASSWORD,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'token',
                     ],
                 ])
                 ->assertJson(['success' => true]);

        $responseData = $response->json('data');
        $this->assertIsString($responseData['token']);
        $this->assertNotEmpty($responseData['token']);
    }
}
