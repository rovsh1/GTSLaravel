<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Module\Shared\Enum\CurrencyEnum;
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
        'currency',
    ];

    protected $casts = [
        'price' => 'float',
        'currency' => CurrencyEnum::class,
    ];

    protected $appends = [
        'has_date_prices'
    ];

    public function hasDatePrices(): Attribute
    {
        return Attribute::get(fn(int|null $val, array $attributes) => (bool)($attributes['has_date_prices'] ?? false));
    }

    public function scopeWithGroup(Builder $builder): void
    {
        $builder
            ->addSelect('hotel_season_prices.*')
            ->join('hotel_price_groups', 'hotel_price_groups.id', '=', 'hotel_season_prices.group_id')
            ->addSelect('hotel_price_groups.rate_id as rate_id')
            ->addSelect('hotel_price_groups.guests_count as guests_count')
            ->addSelect('hotel_price_groups.is_resident as is_resident')
            ->selectSub(
                DB::table('hotel_season_price_calendar')
                    ->selectRaw('1')
                    ->whereColumn('hotel_season_price_calendar.season_id', '=', 'hotel_season_prices.season_id')
                    ->whereColumn('hotel_season_price_calendar.group_id', '=', 'hotel_season_prices.group_id')
                    ->whereColumn('hotel_season_price_calendar.room_id', '=', 'hotel_season_prices.room_id')
                    ->whereColumn('hotel_season_price_calendar.price', '!=', 'hotel_season_prices.price')
                    ->limit(1),
                'has_date_prices'
            );
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
