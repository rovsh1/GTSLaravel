<?php

declare(strict_types=1);

namespace Module\Supplier\Infrastructure\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Infrastructure\Models\Model;

class TransferServicePrice extends Model
{
    protected $table = 'supplier_car_prices';

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'price_net' => 'float',
        'prices_gross' => 'array',
    ];

    public function scopeWhereDate(Builder $builder, CarbonInterface $date): void
    {
        $builder->join('supplier_seasons', 'supplier_seasons.id', 'supplier_car_prices.season_id')
            ->where('supplier_seasons.date_start', '<=', $date)
            ->where('supplier_seasons.date_end', '>=', $date);
    }
}
