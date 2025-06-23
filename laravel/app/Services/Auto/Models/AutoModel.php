<?php

namespace App\Services\Auto\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель для представления моделей автомобилей
 *
 * @property int $id                   Уникальный идентификатор модели
 * @property string $name              Название модели автомобиля
 * @property Carbon $created_at        Дата и время создания записи
 * @property Carbon $updated_at        Дата и время последнего обновления записи
 *
 * @property-read Collection<int, Auto> $autos Связанные автомобили этой модели
 *
 * @method static Builder|AutoModel byId(int $id) Фильтрация по ID модели
 *
 * @OA\Schema(
 *      schema="AutoModel",
 *      title="AutoModel",
 *      description="Модель описывает марку автомобиля",
 *      required={"name"},
 *      @OA\Property(property="id", type="integer", example=1, description="ID модели автомобиля"),
 *      @OA\Property(property="name", type="string", example="X5", description="Название модели автомобиля"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z", description="Дата создания записи"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-02T16:30:00Z", description="Дата последнего обновления записи"),
 *      @OA\Property(
 *          property="autos",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/Auto"),
 *          description="Список автомобилей, относящихся к данной модели"
 *      )
 * )
 */
class AutoModel extends Model
{
    use HasFactory;

    /**
     * @var bool Использовать временные метки
     */
    public $timestamps = true;

    /**
     * @var string Таблица в базе данных
     */
    protected $table = 'auto_models';

    /**
     * @var string[] Массово заполняемые атрибуты
     */
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[] Преобразование типов атрибутов
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Связь с автомобилями этой модели
     *
     * @return HasMany<Auto>
     */
    public function autos(): HasMany
    {
        return $this->hasMany(Auto::class, 'auto_model_id');
    }

    /**
     * Фильтрация по ID модели
     *
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeById(Builder $query, int $id): Builder
    {
        return $query->where('id', $id);
    }
}
