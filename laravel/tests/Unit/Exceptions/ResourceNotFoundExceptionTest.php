<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Unit-тесты для ResourceNotFoundException.
 *
 * Проверяет HTTP-статус 404, формирование сообщений при разных типах $id
 * (int, string, null), корректность контекста и цепочку наследования.
 */
class ResourceNotFoundExceptionTest extends TestCase
{
    /**
     * Проверяет, что HTTP-статус всегда равен 404.
     */
    public function test_http_status_code_is_404(): void
    {
        $exception = new ResourceNotFoundException(resource: 'User', id: 1);

        $this->assertSame(404, $exception->getHttpStatusCode());
    }

    /**
     * Проверяет, что сообщение формируется с именем ресурса и int-идентификатором.
     */
    public function test_message_contains_resource_name_and_int_id(): void
    {
        $exception = new ResourceNotFoundException(resource: 'Auto', id: 42);

        $this->assertSame('Auto with ID 42 not found', $exception->getMessage());
    }

    /**
     * Проверяет, что сообщение формируется с именем ресурса и string-идентификатором.
     */
    public function test_message_contains_resource_name_and_string_id(): void
    {
        $exception = new ResourceNotFoundException(resource: 'User', id: 'abc-123');

        $this->assertSame('User with ID abc-123 not found', $exception->getMessage());
    }

    /**
     * Проверяет, что сообщение формируется без идентификатора, когда $id равен null.
     */
    public function test_message_without_id_when_id_is_null(): void
    {
        $exception = new ResourceNotFoundException(resource: 'Category');

        $this->assertSame('Category not found', $exception->getMessage());
    }

    /**
     * Проверяет, что контекст содержит id и resource, когда id передан.
     */
    public function test_context_contains_id_and_resource_when_id_provided(): void
    {
        $exception = new ResourceNotFoundException(resource: 'Auto', id: 5);

        $context = $exception->getContext();

        $this->assertArrayHasKey('id', $context);
        $this->assertArrayHasKey('resource', $context);
        $this->assertSame(5, $context['id']);
        $this->assertSame('Auto', $context['resource']);
    }

    /**
     * Проверяет, что контекст содержит только resource, когда id не передан.
     */
    public function test_context_contains_only_resource_when_id_is_null(): void
    {
        $exception = new ResourceNotFoundException(resource: 'Mark');

        $context = $exception->getContext();

        $this->assertArrayNotHasKey('id', $context);
        $this->assertArrayHasKey('resource', $context);
        $this->assertSame('Mark', $context['resource']);
    }

    /**
     * Проверяет, что контекст содержит строковый id при передаче string-идентификатора.
     */
    public function test_context_contains_string_id(): void
    {
        $exception = new ResourceNotFoundException(resource: 'Token', id: 'token-xyz');

        $context = $exception->getContext();

        $this->assertSame('token-xyz', $context['id']);
    }

    /**
     * Проверяет, что предыдущее исключение корректно сохраняется.
     */
    public function test_previous_exception_is_stored(): void
    {
        $previous  = new Exception('DB error');
        $exception = new ResourceNotFoundException(
            resource: 'User',
            id: 1,
            previous: $previous,
        );

        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * Проверяет, что ResourceNotFoundException является наследником ServiceException.
     */
    public function test_resource_not_found_exception_extends_service_exception(): void
    {
        $exception = new ResourceNotFoundException(resource: 'User', id: 1);

        $this->assertInstanceOf(ServiceException::class, $exception);
    }

    /**
     * Проверяет, что внутренний код ошибки равен 0.
     */
    public function test_internal_code_is_zero(): void
    {
        $exception = new ResourceNotFoundException(resource: 'User', id: 1);

        $this->assertSame(0, $exception->getCode());
    }
}
