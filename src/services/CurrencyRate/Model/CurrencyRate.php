<?php

namespace Services\CurrencyRate\Model;

use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\ValueObject\CountryEnum;

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
