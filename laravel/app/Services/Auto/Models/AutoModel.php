<?php

namespace App\Services\Auto\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id ID
 * @property string $name Наименование марки
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property AutoMark $mark
 *
 * @OA\Schema(schema="AutoModel",
 *   @OA\Property(property="id", type="integer", description="ID", example="1"),
 *   @OA\Property(property="name", type="string", description="AutoModel name", example="M6"),
 *   @OA\Property(property="created_at", type="datetime", description="Created at", example="2025-01-01 00:00:01"),
 *   @OA\Property(property="updated_at", type="datetime", description="Updated at", example="2025-01-01 00:00:01")
 * )
 *
 * @package App\Services\Auto\Models
 */
class AutoModel extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = true;
    /**
     * @var string
     */
    protected $table = 'auto_models';
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
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
}
