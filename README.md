# Test API — REST API для управления автопарком

REST API на Laravel 12 для управления автомобилями, марками и моделями. Поддерживает аутентификацию через токены (Sanctum), версионирование маршрутов (v1, v2) и документацию через Swagger/OpenAPI.

---

## Стек технологий

| Компонент        | Версия / Технология        |
|------------------|----------------------------|
| PHP              | 8.2                        |
| Фреймворк        | Laravel 12                 |
| База данных      | MySQL                      |
| Аутентификация   | Laravel Sanctum            |
| DTO              | spatie/laravel-data        |
| Документация API | zircote/swagger-php        |
| Веб-сервер       | Nginx (Alpine)             |
| Контейнеризация  | Docker / Docker Compose    |

---

## Архитектура

Проект реализует модульную сервисную архитектуру со слоями:

```
Controller -> Service -> Repository -> Model
```

Каждый бизнес-модуль располагается в `app/Services/{Module}/` и содержит:

- `Http/Controllers/` — принимают запросы, вызывают сервис, возвращают ответ
- `Http/Requests/` — валидация входных данных через FormRequest
- `DTO/Request/`, `DTO/Response/` — объекты передачи данных (на базе spatie/laravel-data)
- `Services/` — бизнес-логика, не работает с БД напрямую
- `Repositories/` — инкапсуляция запросов к БД через Eloquent
- `Models/` — Eloquent-модели
- `Resources/` — API-ресурсы (форматирование ответов)
- `Interfaces/` — контракты сервисов и репозиториев
- `Providers/` — ServiceProvider модуля (биндинги, миграции)

Существующие модули: `Auto` (управление автопарком), `User` (аутентификация).

Все ответы возвращаются в едином формате через класс `ApiResponse`:

```json
{ "success": true, "data": { ... } }
{ "success": false, "message": "...", "errors": { ... } }
```

---

## Установка и запуск

### Требования

- Docker и Docker Compose
- Внешняя Docker-сеть `global_net`

### Шаги

**1. Создать внешнюю Docker-сеть (один раз):**

```bash
docker network create global_net
```

**2. Перейти в директорию с Docker-конфигурацией:**

```bash
cd _dev
```

**3. Создать `.env` файл из примера:**

```bash
cp .env.example .env
```

**4. Запустить контейнеры:**

```bash
docker-compose up -d
```

Запустятся три контейнера:
- `nginx_testAPI` — Nginx (порт `8080`)
- `php_testAPI` — PHP-FPM 8.2
- `mysql_testAPI` — MySQL (порт `3306`)

**5. Установить зависимости:**

```bash
docker exec -it php_testAPI bash
composer install --ignore-platform-reqs
```

**6. Настроить приложение (внутри контейнера `php_testAPI`):**

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Приложение доступно по адресу: `http://localhost:8080`

---

## API Endpoints

Базовый URL: `http://localhost:8080/api/v1`

### Аутентификация

| Метод | URL           | Описание          | Авторизация |
|-------|---------------|-------------------|-------------|
| POST  | /auth/login   | Вход в систему    | Нет         |

### Автомобили

| Метод  | URL          | Описание                        | Авторизация |
|--------|--------------|---------------------------------|-------------|
| GET    | /auto        | Список автомобилей              | Да          |
| POST   | /auto        | Создать автомобиль              | Да          |
| PUT    | /auto/{id}   | Обновить автомобиль             | Да          |
| DELETE | /auto/{id}   | Удалить автомобиль              | Да          |

### Марки автомобилей

| Метод  | URL           | Описание              | Авторизация |
|--------|---------------|-----------------------|-------------|
| GET    | /marks        | Список марок          | Да          |
| GET    | /marks/{id}   | Получить марку        | Да          |
| POST   | /marks        | Создать марку         | Да          |
| PUT    | /marks/{id}   | Обновить марку        | Да          |
| DELETE | /marks/{id}   | Удалить марку         | Да          |

### Модели автомобилей

| Метод  | URL            | Описание               | Авторизация |
|--------|----------------|------------------------|-------------|
| GET    | /models        | Список моделей         | Да          |
| GET    | /models/{id}   | Получить модель        | Да          |
| POST   | /models        | Создать модель         | Да          |
| PUT    | /models/{id}   | Обновить модель        | Да          |
| DELETE | /models/{id}   | Удалить модель         | Да          |

Для защищённых маршрутов передавайте заголовок:

```
Authorization: Bearer {token}
```

---

## Запуск тестов

Тесты выполняются внутри контейнера `php_testAPI`. Тесты используют SQLite in-memory базу данных и не требуют запущенного MySQL.

```bash
docker exec -it php_testAPI bash
php artisan test
```

Запуск конкретного теста:

```bash
php artisan test --filter=ExampleTest
```

---

## Swagger-документация

Документация API генерируется через аннотации `zircote/swagger-php`. Аннотации расположены в контроллерах, FormRequest-классах и API-ресурсах.

Для генерации документации выполните (внутри контейнера):

```bash
php artisan l5-swagger:generate
```

После генерации документация доступна по адресу: `http://localhost:8080/api/documentation`

---

## Структура проекта

```
test_API/
├── _dev/                   # Docker-конфигурация (nginx, php, mysql)
│   ├── docker-compose.yml
│   ├── nginx/
│   └── php/
├── laravel/                # Laravel-приложение
│   ├── app/
│   │   ├── Services/
│   │   │   ├── Auto/       # Модуль управления автопарком
│   │   │   └── User/       # Модуль аутентификации
│   │   ├── Http/
│   │   │   └── Responses/  # ApiResponse — единый формат ответов
│   │   └── Exceptions/     # Кастомный обработчик ошибок
│   ├── routes/
│   │   ├── v1/api.php
│   │   └── v2/api.php
│   └── tests/
└── Example/                # Пример кода бронирования (не связан с основным приложением)
```
