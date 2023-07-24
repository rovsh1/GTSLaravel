<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Model;

use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    public $timestamps = false;

    protected $table = 'r_currency_rates';

    protected $fillable = [
        'country',
        'currency',
        'date',
        'value',
        'nominal'
    ];

    protected $casts = [
        'country' => CountryEnum::class,
        'currency' => CurrencyEnum::class,
        'date' => 'date',
        'value' => 'float',
        'nominal' => 'int'
    ];
}