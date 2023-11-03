<?php

namespace Module\Client\Infrastructure\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $table = 'client_currency_rates';

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'date_start' => 'date',
        'date_end' => 'date',
        'rate' => 'float',
    ];

    public function scopeWhereIncludeDate(Builder $builder, CarbonInterface $date): void
    {
        $builder->whereDate('date_start', '<=', $date)
            ->whereDate('date_end', '>=', $date);
    }

    public function scopeWhereHotelId(Builder $builder, int $hotelId): void
    {
        $builder->whereExists(function (Query $query) use ($hotelId) {
            $query->selectRaw(1)
                ->from('client_currency_rate_hotels')
                ->whereColumn('client_currency_rate_hotels.rate_id', 'client_currency_rates.id')
                ->where('hotel_id', $hotelId);
        });
    }
}
