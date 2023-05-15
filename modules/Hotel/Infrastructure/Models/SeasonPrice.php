<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Shared\Infrastructure\Models\Model;

class SeasonPrice extends Model
{
    public $timestamps = false;

    protected $table = 'hotel_season_prices';

    protected $fillable = [
        'season_id',
        'group_id',
        'room_id',
        'price',
        'currency_id',
    ];

    public function scopeWithGroup(Builder $builder): void
    {
        $builder
            ->addSelect('hotel_season_prices.*')
            ->join('hotel_price_groups', 'hotel_price_groups.id', '=', 'hotel_season_prices.group_id')
            ->addSelect('hotel_price_groups.rate_id as rate_id')
            ->addSelect('hotel_price_groups.guests_number as guests_number')
            ->addSelect('hotel_price_groups.is_resident as is_resident');
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId): void
    {
        $builder->whereHas('season', function (Builder $query) use ($hotelId) {
            $query->whereHas('contract', function (Builder $query) use ($hotelId) {
                $query->whereHotelId($hotelId);
            });
        });
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id', 'id');
    }
}
