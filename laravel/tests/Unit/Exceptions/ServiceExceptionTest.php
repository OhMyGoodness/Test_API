<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\ServiceException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Unit-тесты для ServiceException.
 *
 * Проверяет конструктор, методы getHttpStatusCode(), getContext()
 * и getMessage() при различных вариантах входных данных.
 */
class ServiceExceptionTest extends TestCase
{
    /**
     * Проверяет, что конструктор корректно устанавливает сообщение,
     * HTTP-статус и контекст при передаче всех аргументов.
     */
    public function test_constructor_sets_message_status_and_context(): void
    {
        $exception = new ServiceException(
            message: 'Something went wrong',
            httpStatusCode: 422,
            code: 10,
            context: ['field' => 'email'],
        );

        $this->assertSame('Something went wrong', $exception->getMessage());
        $this->assertSame(422, $exception->getHttpStatusCode());
        $this->assertSame(10, $exception->getCode());
        $this->assertSame(['field' => 'email'], $exception->getContext());
    }

    /**
     * Проверяет, что HTTP-статус по умолчанию равен 500 при его отсутствии.
     */
    public function test_default_http_status_code_is_500(): void
    {
        $exception = new ServiceException(message: 'Server error');

        $this->assertSame(500, $exception->getHttpStatusCode());
    }

    /**
     * Проверяет, что внутренний код ошибки по умолчанию равен 0.
     */
    public function test_default_code_is_zero(): void
    {
        $exception = new ServiceException(message: 'Error');

        $this->assertSame(0, $exception->getCode());
    }

    /**
     * Проверяет, что контекст по умолчанию — пустой массив.
     */
    public function test_default_context_is_empty_array(): void
    {
        $exception = new ServiceException(message: 'Error');

        $this->assertSame([], $exception->getContext());
    }

    /**
     * Проверяет, что контекст может содержать несколько ключей
     * с разными типами значений.
     */
    public function test_context_with_multiple_keys(): void
    {
        $context = [
            'user_id' => 42,
            'action'  => 'update',
            'details' => ['field' => 'name'],
        ];

        $exception = new ServiceException(
            message: 'Context error',
            context: $context,
        );

        $this->assertSame($context, $exception->getContext());
    }

    /**
     * Проверяет, что предыдущее исключение корректно сохраняется при передаче через конструктор.
     */
    public function test_previous_exception_is_set(): void
    {
        $previous  = new Exception('Original error');
        $exception = new ServiceException(
            message: 'Wrapper error',
            previous: $previous,
        );

        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * Проверяет, что ServiceException является наследником Exception.
     */
    public function test_service_exception_extends_exception(): void
    {
        $exception = new ServiceException(message: 'Test');

        $this->assertInstanceOf(Exception::class, $exception);
    }

    /**
     * Проверяет корректную установку произвольного HTTP-статуса.
     */
    public function test_custom_http_status_codes(): void
    {
        $cases = [400, 401, 403, 404, 409, 422, 500, 503];

        foreach ($cases as $status) {
            $exception = new ServiceException(message: 'Error', httpStatusCode: $status);
            $this->assertSame($status, $exception->getHttpStatusCode(), "Ожидался HTTP-статус {$status}");
        }
    }
}
