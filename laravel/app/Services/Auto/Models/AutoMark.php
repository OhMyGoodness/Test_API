<?php

namespace App\Services\Auto\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель для представления марок автомобилей
 *
 * @property int $id                   Уникальный идентификатор марки
 * @property string $name              Название марки автомобиля
 * @property Carbon $created_at        Дата и время создания записи
 * @property Carbon $updated_at        Дата и время обновления записи
 *
 * @property-read Collection<int, Auto> $autos Связанные автомобили этой марки
 *
 * @method static Builder|AutoMark byId(int $id) Фильтрация по ID марки
 *
 * @OA\Schema(
 *      schema="AutoMark",
 *      title="AutoMark",
 *      description="Марка автомобиля",
 *      required={"name"},
 *      @OA\Property(property="id", type="integer", example=1, description="ID марки автомобиля"),
 *      @OA\Property(property="name", type="string", example="BMW", description="Название марки автомобиля"),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z", description="Дата создания записи"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-02T16:30:00Z", description="Дата последнего обновления записи"),
 *      @OA\Property(
 *          property="autos",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/Auto"),
 *          description="Список автомобилей, принадлежащих данной марке"
 *      )
 * )
 */
class AutoMark extends Model
{
    use HasFactory;

    /**
     * @var bool Включение временных меток
     */
    public $timestamps = true;

    /**
     * @var string Таблица в базе данных
     */
    protected $table = 'auto_marks';

    /**
     * @var string[] Доступные для массового заполнения атрибуты
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var string[] Преобразование типов атрибутов
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Связь с автомобилями этой марки
     *
     * @return HasMany<Auto>
     */
    public function autos(): HasMany
    {
        return $this->hasMany(Auto::class, 'auto_mark_id');
    }

    /**
     * Фильтрация по ID марки
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
