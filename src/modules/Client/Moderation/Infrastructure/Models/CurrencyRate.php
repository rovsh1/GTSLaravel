<?php

namespace Module\Client\Moderation\Infrastructure\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

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
}
