<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Infrastructure\Models\Model;

class AirportServicePrice extends Model
{
    protected $table = 'supplier_airport_prices';

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'price_net' => 'float',
        'prices_gross' => 'array',
    ];

    public function scopeWhereDate(Builder $builder, CarbonInterface $date): void
    {
        $builder->join('supplier_seasons', 'supplier_seasons.id', 'supplier_airport_prices.season_id')
            ->where('supplier_seasons.date_start', '<=', $date->clone()->startOfDay())
            ->where('supplier_seasons.date_end', '>=', $date->clone()->endOfDay());
    }
}