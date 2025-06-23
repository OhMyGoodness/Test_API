<?php

namespace App\Services\Auto\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель для представления автомобилей
 *
 * @property int $id            Уникальный идентификатор автомобиля
 * @property int $year          Год выпуска автомобиля
 * @property int $mileage       Пробег автомобиля
 * @property string $color      Цвет автомобиля
 * @property int $auto_model_id ID модели автомобиля (связь с AutoModel)
 * @property int $auto_mark_id  ID марки автомобиля (связь с AutoMark)
 * @property int $user_id       ID пользователя
 * @property Carbon $created_at Дата и время создания записи
 * @property Carbon $updated_at Дата и время последнего обновления записи
 *
 * @property-read AutoModel $model Модель автомобиля (связь belongsTo)
 * @property-read AutoMark $mark   Марка автомобиля (связь belongsTo)
 *
 * @method static Builder|Auto byId(int $id)                  Фильтрация по ID автомобиля
 * @method static Builder|Auto byUserId(int $userId)          Фильтрация по ID пользователя
 *
 * @OA\Schema(
 *      schema="Auto",
 *      title="Auto",
 *      description="Модель автомобиля",
 *      required={"year", "mileage", "color", "auto_model_id", "auto_mark_id"},
 *      @OA\Property(property="id", type="integer", example=1, description="Уникальный идентификатор автомобиля"),
 *      @OA\Property(property="year", type="integer", example=2020, description="Год выпуска автомобиля"),
 *      @OA\Property(property="mileage", type="integer", example=50000, description="Пробег автомобиля"),
 *      @OA\Property(property="color", type="string", example="Чёрный", description="Цвет автомобиля"),
 *      @OA\Property(
 *          property="model",
 *          ref="#/components/schemas/AutoModelResource",
 *          description="Связанная модель автомобиля"
 *      ),
 *      @OA\Property(
 *          property="mark",
 *          ref="#/components/schemas/AutoMarkResource",
 *          description="Связанная марка автомобиля"
 *      ),
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2023-06-01T12:00:00Z", description="Дата создания записи"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2023-06-10T14:30:00Z", description="Дата последнего обновления записи")
 * )
 */
class Auto extends Model
{
    use HasFactory;

    /**
     * @var string Таблица в базе данных
     */
    protected $table = 'autos';

    /**
     * @var string[] Доступные для массового заполнения атрибуты
     */
    protected $fillable = [
        'year',
        'mileage',
        'color',
        'auto_model_id',
        'auto_mark_id',
        'user_id',
    ];

    /**
     * @var string[] Скрытые атрибуты
     */
    protected $hidden = ['user_id'];

    /**
     * Преобразование типов атрибутов
     *
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'year'          => 'integer',
            'mileage'       => 'integer',
            'auto_model_id' => 'integer',
            'auto_mark_id'  => 'integer',
            'user_id'       => 'integer',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    /**
     * Связь с моделью автомобиля
     *
     * @return BelongsTo<AutoModel, Auto>
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(AutoModel::class, 'auto_model_id');
    }

    /**
     * Связь с маркой автомобиля
     *
     * @return BelongsTo<AutoMark, Auto>
     */
    public function mark(): BelongsTo
    {
        return $this->belongsTo(AutoMark::class, 'auto_mark_id');
    }

    /**
     * Фильтрация по ID автомобиля
     *
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeById(Builder $query, int $id): Builder
    {
        return $query->where('id', $id);
    }

    /**
     * Фильтрация по ID пользователя
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
