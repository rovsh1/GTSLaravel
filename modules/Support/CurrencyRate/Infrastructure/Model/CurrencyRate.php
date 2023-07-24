<?php

namespace Module\Support\CurrencyRate\Infrastructure\Model;

use Module\Shared\Enum\CurrencyEnum;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;
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
