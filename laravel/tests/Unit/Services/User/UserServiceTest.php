<?php

declare(strict_types=1);

namespace Tests\Unit\Services\User;

use App\Exceptions\ResourceNotFoundException;
use App\Services\User\DTO\UserLoginDTO;
use App\Services\User\Models\User;
use App\Services\User\UserService;
use Tests\TestCase;

/**
 * Unit-тесты для UserService.
 *
 * Тесты проверяют метод login: успешную авторизацию, обработку
 * несуществующего email и неверного пароля.
 * Используется SQLite in-memory — реальная внешняя БД не требуется.
 */
class UserServiceTest extends TestCase
{
    /**
     * Экземпляр тестируемого сервиса.
     */
    private UserService $service;

    /**
     * Инициализация окружения перед каждым тестом.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new UserService();
    }

    /**
     * Успешный логин: email найден и пароль совпадает — должна вернуться строка токена.
     *
     * @return void
     */
    public function test_login_returns_token_string_when_credentials_are_correct(): void
    {
        User::create([
            'name'     => 'Test User',
            'email'    => 'user@example.com',
            'password' => 'secret123',
        ]);

        $dto = new UserLoginDTO(
            email: 'user@example.com',
            password: 'secret123',
        );

        $token = $this->service->login($dto);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    /**
     * Несуществующий email: пользователь не найден — должен бросить ResourceNotFoundException.
     *
     * @return void
     */
    public function test_login_throws_resource_not_found_exception_when_email_does_not_exist(): void
    {
        $dto = new UserLoginDTO(
            email: 'nonexistent@example.com',
            password: 'any-password',
        );

        $this->expectException(ResourceNotFoundException::class);

        $this->service->login($dto);
    }

    /**
     * Неверный пароль: пользователь найден, но пароль не совпадает — должен бросить ResourceNotFoundException.
     *
     * @return void
     */
    public function test_login_throws_resource_not_found_exception_when_password_is_wrong(): void
    {
        User::create([
            'name'     => 'Test User',
            'email'    => 'user@example.com',
            'password' => 'correct-password',
        ]);

        $dto = new UserLoginDTO(
            email: 'user@example.com',
            password: 'wrong-password',
        );

        $this->expectException(ResourceNotFoundException::class);

        $this->service->login($dto);
    }
}
