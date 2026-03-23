<?php

declare(strict_types=1);

namespace App\Services\User\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Модель пользователя.
 *
 * @package App\Services\User\Models
 *
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="Модель пользователя",
 *     required={"email", "password"},
 *     @OA\Property(property="id", type="integer", example=1, description="Уникальный идентификатор пользователя"),
 *     @OA\Property(property="name", type="string", example="John Doe", description="Имя пользователя"),
 *     @OA\Property(property="email", type="string", example="user@example.com", description="Электронная почта пользователя"),
 *     @OA\Property(property="password", type="string", format="password", readOnly=true, description="Пароль пользователя"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z", description="Дата создания записи"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-02T14:45:00Z", description="Дата последнего обновления записи")
 * )
 *
 * @property int $id Идентификатор пользователя
 * @property string $name Имя пользователя
 * @property string $email Электронная почта
 * @property string $password Хэш пароля
 * @property \Illuminate\Support\Carbon|null $created_at Дата создания
 * @property \Illuminate\Support\Carbon|null $updated_at Дата обновления
 *
 * @method static Builder|User byEmail(string $email) Фильтрация по email
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @var array<int, string> Поля, доступные для массового заполнения */
    protected $fillable = ['name', 'email', 'password'];

    /** @var array<int, string> Скрытые поля */
    protected $hidden = ['password', 'remember_token'];

    /** @var array<string, string> Приведение типов атрибутов */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Скоуп для фильтрации пользователя по email.
     *
     * @param Builder $builder Объект построителя запросов
     * @param string $email Электронная почта пользователя
     * @return Builder
     */
    public function scopeByEmail(Builder $builder, string $email): Builder
    {
        return $builder->where('email', $email);
    }
}
