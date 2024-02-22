<?php

namespace Pkg\CurrencyRate\Model;

use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\CurrencyEnum;

class CurrencyRate extends Model
{
    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

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
