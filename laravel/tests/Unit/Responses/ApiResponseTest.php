<?php

declare(strict_types=1);

namespace Tests\Unit\Responses;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * Unit-тесты для ApiResponse.
 *
 * Проверяет структуру и HTTP-статусы ответов, возвращаемых статическими методами:
 * success(), created(), error(), noContent().
 */
class ApiResponseTest extends TestCase
{
    // -------------------------------------------------------------------------
    // success()
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что success() возвращает JsonResponse с полем success = true
     * и переданными данными в поле data.
     */
    public function test_success_returns_json_response_with_success_true_and_data(): void
    {
        $data = ['id' => 1, 'name' => 'Toyota'];

        $response = ApiResponse::success($data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $content = $response->getData(true);
        $this->assertTrue($content['success']);
        $this->assertSame($data, $content['data']);
    }

    /**
     * Проверяет, что success() с null в качестве данных возвращает data = null.
     */
    public function test_success_with_null_data_returns_data_null(): void
    {
        $response = ApiResponse::success(null);

        $content = $response->getData(true);

        $this->assertTrue($content['success']);
        $this->assertNull($content['data']);
    }

    /**
     * Проверяет, что success() принимает произвольный HTTP-статус.
     */
    public function test_success_with_custom_status_code(): void
    {
        $response = ApiResponse::success(['key' => 'value'], 202);

        $this->assertSame(202, $response->getStatusCode());
    }

    /**
     * Проверяет, что success() без аргументов возвращает HTTP 200 и data = null.
     */
    public function test_success_without_arguments_returns_200_and_null_data(): void
    {
        $response = ApiResponse::success();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $content = $response->getData(true);
        $this->assertTrue($content['success']);
        $this->assertNull($content['data']);
    }

    /**
     * Проверяет, что success() может принимать строку в качестве данных.
     */
    public function test_success_with_string_data(): void
    {
        $response = ApiResponse::success('plain string');

        $content = $response->getData(true);

        $this->assertSame('plain string', $content['data']);
    }

    // -------------------------------------------------------------------------
    // created()
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что created() возвращает HTTP-статус 201.
     */
    public function test_created_returns_status_201(): void
    {
        $response = ApiResponse::created(['id' => 1]);

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * Проверяет, что created() возвращает структуру {success: true, data: ...}.
     */
    public function test_created_returns_success_true_and_data(): void
    {
        $data = ['id' => 5, 'name' => 'BMW'];

        $response = ApiResponse::created($data);

        $content = $response->getData(true);

        $this->assertTrue($content['success']);
        $this->assertSame($data, $content['data']);
    }

    /**
     * Проверяет, что created() корректно обрабатывает null данные.
     */
    public function test_created_with_null_data(): void
    {
        $response = ApiResponse::created(null);

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $content = $response->getData(true);
        $this->assertTrue($content['success']);
        $this->assertNull($content['data']);
    }

    // -------------------------------------------------------------------------
    // error()
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что error() возвращает JsonResponse с success = false
     * и переданным сообщением об ошибке.
     */
    public function test_error_returns_json_response_with_success_false_and_message(): void
    {
        $response = ApiResponse::error('Something went wrong');

        $this->assertInstanceOf(JsonResponse::class, $response);

        $content = $response->getData(true);

        $this->assertFalse($content['success']);
        $this->assertSame('Something went wrong', $content['message']);
    }

    /**
     * Проверяет, что error() возвращает HTTP-статус 400 по умолчанию.
     */
    public function test_error_default_status_code_is_400(): void
    {
        $response = ApiResponse::error('Bad request');

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Проверяет, что error() принимает произвольный HTTP-статус.
     */
    public function test_error_with_custom_status_code(): void
    {
        $response = ApiResponse::error('Not found', 404);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * Проверяет, что поле errors отсутствует в ответе, когда массив ошибок пустой.
     */
    public function test_error_without_errors_array_does_not_include_errors_key(): void
    {
        $response = ApiResponse::error('Error message');

        $content = $response->getData(true);

        $this->assertArrayNotHasKey('errors', $content);
    }

    /**
     * Проверяет, что поле errors присутствует в ответе, когда передан непустой массив ошибок.
     */
    public function test_error_with_errors_array_includes_errors_key(): void
    {
        $errors = ['email' => ['Email is required'], 'name' => ['Name is too short']];

        $response = ApiResponse::error('Validation failed', 422, $errors);

        $content = $response->getData(true);

        $this->assertArrayHasKey('errors', $content);
        $this->assertSame($errors, $content['errors']);
    }

    /**
     * Проверяет, что error() с HTTP-статусом 500 корректно устанавливает статус.
     */
    public function test_error_with_500_status_code(): void
    {
        $response = ApiResponse::error('Internal server error', 500);

        $this->assertSame(500, $response->getStatusCode());
    }

    /**
     * Проверяет, что error() с HTTP-статусом 401 возвращает 401.
     */
    public function test_error_with_401_status_code(): void
    {
        $response = ApiResponse::error('Unauthorized', 401);

        $this->assertSame(401, $response->getStatusCode());
    }

    // -------------------------------------------------------------------------
    // noContent()
    // -------------------------------------------------------------------------

    /**
     * Проверяет, что noContent() возвращает HTTP-статус 204.
     */
    public function test_no_content_returns_status_204(): void
    {
        $response = ApiResponse::noContent();

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * Проверяет, что noContent() возвращает JsonResponse.
     */
    public function test_no_content_returns_json_response(): void
    {
        $response = ApiResponse::noContent();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Проверяет, что тело ответа noContent() пустое (пустой JSON-объект).
     *
     * JsonResponse при передаче null заменяет его на пустой ArrayObject,
     * поэтому содержимое ответа сериализуется в пустой JSON-объект "{}".
     */
    public function test_no_content_has_empty_body(): void
    {
        $response = ApiResponse::noContent();

        $this->assertSame('{}', $response->getContent());
    }
}
