<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Model;

use Custom\Framework\Database\Eloquent\Model;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;

class CurrencyRate extends Model
{
    public $timestamps = false;

    protected $table = 'r_currency_rates';

    protected $fillable = [
        'country',
        'currency',
        'date',
        'value'
    ];

    protected $casts = [
        'country' => CountryEnum::class,
        'currency' => CurrencyEnum::class,
        'date' => 'date',
        'value' => 'float'
    ];
}
