<?php

declare(strict_types=1);

namespace Tests\Unit\DTO;

use App\Services\Auto\DTO\Request\AutoMarkRequestDTO;
use App\Services\Auto\DTO\Request\AutoModelRequestDTO;
use App\Services\Auto\DTO\Request\AutoRequestDTO;
use App\Services\Auto\DTO\Response\AutoMarkResponseDTO;
use App\Services\Auto\DTO\Response\AutoModelResponseDTO;
use App\Services\Auto\DTO\Response\AutoResponseDTO;
use App\Services\User\DTO\UserLoginDTO;
use Tests\TestCase;

/**
 * Unit-тесты для всех DTO автомобилей и пользователя.
 *
 * Проверяет корректность создания, заполнения полей и сериализации
 * для Request DTO, Response DTO и UserLoginDTO.
 *
 * Наследует Tests\TestCase для загрузки Laravel-контейнера,
 * необходимого методу toArray() пакета spatie/laravel-data.
 */
class AutoDtoTest extends TestCase
{
    // -------------------------------------------------------------------------
    // AutoMarkRequestDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что AutoMarkRequestDTO корректно создаётся через конструктор
     * и поле name заполняется переданным значением.
     */
    public function test_auto_mark_request_dto_constructor_sets_name(): void
    {
        $dto = new AutoMarkRequestDTO(name: 'Toyota');

        $this->assertSame('Toyota', $dto->name);
    }

    /**
     * Проверяет, что toArray() возвращает массив с полем name.
     */
    public function test_auto_mark_request_dto_to_array_contains_name(): void
    {
        $dto = new AutoMarkRequestDTO(name: 'BMW');

        $array = $dto->toArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertSame('BMW', $array['name']);
    }

    /**
     * Проверяет, что AutoMarkRequestDTO принимает пустую строку как значение поля name.
     */
    public function test_auto_mark_request_dto_accepts_empty_string(): void
    {
        $dto = new AutoMarkRequestDTO(name: '');

        $this->assertSame('', $dto->name);
    }

    // -------------------------------------------------------------------------
    // AutoModelRequestDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что AutoModelRequestDTO корректно создаётся через конструктор
     * и поле name заполняется переданным значением.
     */
    public function test_auto_model_request_dto_constructor_sets_name(): void
    {
        $dto = new AutoModelRequestDTO(name: 'Camry');

        $this->assertSame('Camry', $dto->name);
    }

    /**
     * Проверяет, что toArray() возвращает массив с полем name.
     */
    public function test_auto_model_request_dto_to_array_contains_name(): void
    {
        $dto = new AutoModelRequestDTO(name: 'X5');

        $array = $dto->toArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertSame('X5', $array['name']);
    }

    // -------------------------------------------------------------------------
    // AutoRequestDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что AutoRequestDTO корректно создаётся через конструктор
     * и все поля заполняются переданными значениями.
     */
    public function test_auto_request_dto_constructor_sets_all_fields(): void
    {
        $dto = new AutoRequestDTO(
            year: 2020,
            mileage: 50000,
            color: 'red',
            auto_model_id: 3,
            auto_mark_id: 7,
        );

        $this->assertSame(2020, $dto->year);
        $this->assertSame(50000, $dto->mileage);
        $this->assertSame('red', $dto->color);
        $this->assertSame(3, $dto->auto_model_id);
        $this->assertSame(7, $dto->auto_mark_id);
    }

    /**
     * Проверяет, что toArray() возвращает все поля AutoRequestDTO.
     */
    public function test_auto_request_dto_to_array_contains_all_fields(): void
    {
        $dto = new AutoRequestDTO(
            year: 2021,
            mileage: 12000,
            color: 'blue',
            auto_model_id: 1,
            auto_mark_id: 2,
        );

        $array = $dto->toArray();

        $this->assertArrayHasKey('year', $array);
        $this->assertArrayHasKey('mileage', $array);
        $this->assertArrayHasKey('color', $array);
        $this->assertArrayHasKey('auto_model_id', $array);
        $this->assertArrayHasKey('auto_mark_id', $array);

        $this->assertSame(2021, $array['year']);
        $this->assertSame(12000, $array['mileage']);
        $this->assertSame('blue', $array['color']);
        $this->assertSame(1, $array['auto_model_id']);
        $this->assertSame(2, $array['auto_mark_id']);
    }

    // -------------------------------------------------------------------------
    // AutoMarkResponseDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что AutoMarkResponseDTO корректно создаётся через конструктор
     * и все поля заполняются переданными значениями.
     */
    public function test_auto_mark_response_dto_constructor_sets_all_fields(): void
    {
        $dto = new AutoMarkResponseDTO(id: 1, name: 'Toyota');

        $this->assertSame(1, $dto->id);
        $this->assertSame('Toyota', $dto->name);
    }

    /**
     * Проверяет, что toArray() возвращает поля id и name AutoMarkResponseDTO.
     */
    public function test_auto_mark_response_dto_to_array_contains_id_and_name(): void
    {
        $dto = new AutoMarkResponseDTO(id: 5, name: 'Honda');

        $array = $dto->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertSame(5, $array['id']);
        $this->assertSame('Honda', $array['name']);
    }

    // -------------------------------------------------------------------------
    // AutoModelResponseDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что AutoModelResponseDTO корректно создаётся через конструктор
     * и все поля заполняются переданными значениями.
     */
    public function test_auto_model_response_dto_constructor_sets_all_fields(): void
    {
        $dto = new AutoModelResponseDTO(id: 2, name: 'Corolla');

        $this->assertSame(2, $dto->id);
        $this->assertSame('Corolla', $dto->name);
    }

    /**
     * Проверяет, что toArray() возвращает поля id и name AutoModelResponseDTO.
     */
    public function test_auto_model_response_dto_to_array_contains_id_and_name(): void
    {
        $dto = new AutoModelResponseDTO(id: 10, name: 'Civic');

        $array = $dto->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertSame(10, $array['id']);
        $this->assertSame('Civic', $array['name']);
    }

    // -------------------------------------------------------------------------
    // AutoResponseDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что AutoResponseDTO корректно создаётся со всеми обязательными полями
     * и вложенными DTO марки и модели.
     */
    public function test_auto_response_dto_constructor_sets_all_fields_with_relations(): void
    {
        $mark  = new AutoMarkResponseDTO(id: 1, name: 'Toyota');
        $model = new AutoModelResponseDTO(id: 2, name: 'Camry');

        $dto = new AutoResponseDTO(
            id: 10,
            year: 2022,
            mileage: 5000,
            color: 'white',
            mark: $mark,
            model: $model,
        );

        $this->assertSame(10, $dto->id);
        $this->assertSame(2022, $dto->year);
        $this->assertSame(5000, $dto->mileage);
        $this->assertSame('white', $dto->color);
        $this->assertSame($mark, $dto->mark);
        $this->assertSame($model, $dto->model);
    }

    /**
     * Проверяет, что AutoResponseDTO может быть создан без марки и модели (поля null по умолчанию).
     */
    public function test_auto_response_dto_mark_and_model_default_to_null(): void
    {
        $dto = new AutoResponseDTO(
            id: 1,
            year: 2019,
            mileage: 80000,
            color: 'black',
        );

        $this->assertNull($dto->mark);
        $this->assertNull($dto->model);
    }

    /**
     * Проверяет, что toArray() AutoResponseDTO содержит все ожидаемые ключи.
     */
    public function test_auto_response_dto_to_array_contains_all_keys(): void
    {
        $dto = new AutoResponseDTO(
            id: 3,
            year: 2018,
            mileage: 30000,
            color: 'silver',
        );

        $array = $dto->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('year', $array);
        $this->assertArrayHasKey('mileage', $array);
        $this->assertArrayHasKey('color', $array);
        $this->assertArrayHasKey('mark', $array);
        $this->assertArrayHasKey('model', $array);
    }

    /**
     * Проверяет, что toArray() AutoResponseDTO включает вложенные данные марки и модели.
     */
    public function test_auto_response_dto_to_array_includes_nested_mark_and_model(): void
    {
        $mark  = new AutoMarkResponseDTO(id: 1, name: 'Ford');
        $model = new AutoModelResponseDTO(id: 4, name: 'Focus');

        $dto = new AutoResponseDTO(
            id: 7,
            year: 2023,
            mileage: 1000,
            color: 'green',
            mark: $mark,
            model: $model,
        );

        $array = $dto->toArray();

        $this->assertIsArray($array['mark']);
        $this->assertSame(1, $array['mark']['id']);
        $this->assertSame('Ford', $array['mark']['name']);

        $this->assertIsArray($array['model']);
        $this->assertSame(4, $array['model']['id']);
        $this->assertSame('Focus', $array['model']['name']);
    }

    // -------------------------------------------------------------------------
    // UserLoginDTO
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что UserLoginDTO корректно создаётся через конструктор
     * и поля email и password заполняются переданными значениями.
     */
    public function test_user_login_dto_constructor_sets_email_and_password(): void
    {
        $dto = new UserLoginDTO(
            email: 'user@example.com',
            password: 'secret123',
        );

        $this->assertSame('user@example.com', $dto->email);
        $this->assertSame('secret123', $dto->password);
    }

    /**
     * Проверяет, что toArray() UserLoginDTO содержит поля email и password.
     */
    public function test_user_login_dto_to_array_contains_email_and_password(): void
    {
        $dto = new UserLoginDTO(
            email: 'admin@test.ru',
            password: 'pass456',
        );

        $array = $dto->toArray();

        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('password', $array);
        $this->assertSame('admin@test.ru', $array['email']);
        $this->assertSame('pass456', $array['password']);
    }
}
