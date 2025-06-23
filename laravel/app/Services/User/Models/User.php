<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Модель для работы с пользователями
 *
 * @package App\Models
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
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|User byEmailAndPassword(string $email, string $password) Фильтрация по email и паролю
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'created_at', 'updated_at'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Скоуп для фильтрации по email и паролю
     *
     * @param Builder $builder
     * @param string $email
     * @param string $password
     * @return Builder
     */
    public function scopeByEmailAndPassword(Builder $builder, string $email, string $password): Builder
    {
        return $builder->where('email', $email)->where('password', $password);
    }
}
