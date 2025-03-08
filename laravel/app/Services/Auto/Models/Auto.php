<?php

namespace App\Services\Auto\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id ID
 * @property int $year Год
 * @property int $mileage Пробег
 * @property string $color Цвет
 * @property int $auto_model_id ID модели
 * @property int $auto_mark_id ID марки
 * @property int|null $user_id ID пользователя
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property AutoMark $mark
 * @property AutoModel $model
 *
 * @method static byId(int $id)
 * @method static byUserId(int $userId)
 *
 *
 * @OA\Schema(schema="Auto",
 *     @OA\Property(property="id", type="integer", description="ID", example="1"),
 *     @OA\Property(property="year", type="integer", description="Year", example="2025"),
 *     @OA\Property(property="mileage", type="integer", description="Mileage", example="10000"),
 *     @OA\Property(property="color", type="string", description="Color", example="Black"),
 *     @OA\Property(property="auto_model_id", type="integer", description="Model ID", example="1"),
 *     @OA\Property(property="auto_mark_id", type="integer", description="Mark ID", example="1"),
 *     @OA\Property(property="user_id", type="integer", description="User ID", example="1", nullable=true),
 *     @OA\Property(property="mark", ref="#/components/schemas/AutoMark"),
 *     @OA\Property(property="model", ref="#/components/schemas/AutoModel"),
 *     @OA\Property(property="created_at", type="datetime", description="Created at", example="2025-01-01 00:00:01"),
 *     @OA\Property(property="updated_at", type="datetime", description="Updated at", example="2025-01-01 00:00:01")
 * )
 *
 * @package App\Services\Auto\Models
 */
class Auto extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'autos';

    /**
     * @var string[]
     */
    protected $fillable = [
        'year',
        'mileage',
        'color',
        'auto_model_id',
        'auto_mark_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $hidden = ['user_id'];

    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function mark(): BelongsTo
    {
        return $this->belongsTo(AutoMark::class, 'auto_mark_id');
    }

    /**
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(AutoModel::class, 'auto_model_id');
    }

    /**
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeById(Builder $query, int $id): Builder
    {
        return $query->where('id', $id);
    }

    /**
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
