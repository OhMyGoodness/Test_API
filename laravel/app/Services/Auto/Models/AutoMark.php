<?php

namespace App\Services\Auto\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id ID
 * @property string $name Название марки
 * @property int $model_id ID модели
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static byId(int $id)
 *
 * @OA\Schema(schema="AutoMark",
 *     @OA\Property(property="id", type="integer", description="ID", example="1"),
 *     @OA\Property(property="name", type="string", description="AutoMark name", example="BMW"),
 *     @OA\Property(property="created_at", type="datetime", description="Created at", example="2025-01-01 00:00:01"),
 *     @OA\Property(property="updated_at", type="datetime", description="Updated at", example="2025-01-01 00:00:01")
 * )
 *
 * @package App\Services\Auto\Models
 */
class AutoMark extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * @var string
     */
    protected $table = 'auto_marks';
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'model_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
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
}
