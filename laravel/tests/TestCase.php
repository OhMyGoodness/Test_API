<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Базовый класс для всех тестов приложения.
 * Использует SQLite in-memory БД для полной изоляции от продакшн-окружения.
 */
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
}
