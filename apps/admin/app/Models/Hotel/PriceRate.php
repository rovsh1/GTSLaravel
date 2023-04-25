<?php

namespace App\Admin\Models\Hotel;

use Custom\Framework\Database\Eloquent\HasTranslations;
use Custom\Framework\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $hotel_id
 * @property string $name
 * @property string $description
 * @property int[] $room_ids
 * @method static \Illuminate\Database\Eloquent\Builder|PriceRate whereHotelId(int $value)
 */
class PriceRate extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $table = 'hotel_price_rates';

    protected $translatable = ['name', 'description'];

    private array $savingRoomIds;

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'room_ids',
    ];

    public static function booted()
    {
        static::addGlobalScope('default', function (Builder $builder) {
            $builder
                ->addSelect('hotel_price_rates.*')
                ->joinTranslations();
        });

        static::saved(function (self $model): void {
            if (isset($model->savingRoomIds)) {
                $model->rooms()->sync($model->savingRoomIds);
                unset($model->savingRoomIds);
            }
        });
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'hotel_price_rate_rooms',
            'rate_id',
            'room_id'
        );
    }

    public function roomIds(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->rooms()->pluck('id')->toArray(),
            set: function (array $roomIds) {
                $this->savingRoomIds = $roomIds;

                return [];
            }
        );
    }

    public function __toString()
    {
        return $this->name;
    }
}
