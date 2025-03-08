<?php

namespace App\Services\User\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id ID
 * @property string $name Name
 * @property string $email E-Mail
 * @property string $password Password
 * @property Carbon $crated_at
 * @property Carbon $updated_at
 *
 * @method static Builder|User byEmailAndPassword($email, $password)
 *
 * @OA\Schema(schema="User",
 *     @OA\Property(property="id", type="integer", description="ID", example="1"),
 *     @OA\Property(property="name", type="integer", description="Name", example="User name"),
 *     @OA\Property(property="email", type="integer", description="E-Mail", example="mail@mail.com"),
 *     @OA\Property(property="created_at", type="datetime", description="Created at", example="2025-01-01 00:00:01"),
 *     @OA\Property(property="updated_at", type="datetime", description="Updated at", example="2025-01-01 00:00:01"),
 * )
 *
 * @package App\Services\User\Models
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    /**
     * @param Builder $builder
     * @param string $email
     * @param string $password
     * @return Builder
     */
    public function scopeByEmailAndPassword(Builder $builder, string $email, string $password): Builder
    {
        return $builder->where(['email' => $email, 'password' => $password]);
    }


}
