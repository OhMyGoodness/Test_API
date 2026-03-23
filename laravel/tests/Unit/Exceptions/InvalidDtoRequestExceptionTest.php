<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\InvalidDtoRequestException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Unit-тесты для InvalidDtoRequestException.
 *
 * Проверяет конструктор с различными наборами аргументов,
 * значения по умолчанию и цепочку наследования.
 */
class InvalidDtoRequestExceptionTest extends TestCase
{
    /**
     * Проверяет, что конструктор корректно устанавливает переданное сообщение.
     */
    public function test_constructor_sets_message(): void
    {
        $exception = new InvalidDtoRequestException(message: 'Invalid DTO data');

        $this->assertSame('Invalid DTO data', $exception->getMessage());
    }

    /**
     * Проверяет, что сообщение по умолчанию — пустая строка.
     */
    public function test_default_message_is_empty_string(): void
    {
        $exception = new InvalidDtoRequestException();

        $this->assertSame('', $exception->getMessage());
    }

    /**
     * Проверяет, что внутренний код ошибки по умолчанию равен 0.
     */
    public function test_default_code_is_zero(): void
    {
        $exception = new InvalidDtoRequestException();

        $this->assertSame(0, $exception->getCode());
    }

    /**
     * Проверяет, что конструктор корректно устанавливает переданный код ошибки.
     */
    public function test_constructor_sets_custom_code(): void
    {
        $exception = new InvalidDtoRequestException(code: 42);

        $this->assertSame(42, $exception->getCode());
    }

    /**
     * Проверяет, что предыдущее исключение корректно сохраняется.
     */
    public function test_previous_exception_is_stored(): void
    {
        $previous  = new Exception('Cause of error');
        $exception = new InvalidDtoRequestException(
            message: 'DTO error',
            previous: $previous,
        );

        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * Проверяет, что по умолчанию предыдущее исключение равно null.
     */
    public function test_default_previous_is_null(): void
    {
        $exception = new InvalidDtoRequestException();

        $this->assertNull($exception->getPrevious());
    }

    /**
     * Проверяет, что InvalidDtoRequestException является наследником Exception.
     */
    public function test_invalid_dto_request_exception_extends_exception(): void
    {
        $exception = new InvalidDtoRequestException();

        $this->assertInstanceOf(Exception::class, $exception);
    }

    /**
     * Проверяет создание исключения со всеми тремя аргументами одновременно.
     */
    public function test_constructor_with_all_arguments(): void
    {
        $previous  = new Exception('Root cause');
        $exception = new InvalidDtoRequestException(
            message: 'Cannot build DTO',
            code: 99,
            previous: $previous,
        );

        $this->assertSame('Cannot build DTO', $exception->getMessage());
        $this->assertSame(99, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
