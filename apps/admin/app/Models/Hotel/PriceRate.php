<?php

namespace App\Admin\Models\Hotel;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PriceRate extends \Module\Hotel\Moderation\Infrastructure\Models\PriceRate
{
    private array $savingRoomIds;

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'meal_plan_id',

        'room_ids',
    ];

    public static function booted()
    {
        parent::booted();

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
