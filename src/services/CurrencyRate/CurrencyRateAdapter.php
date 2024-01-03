<?php

namespace Services\CurrencyRate;

use DateTimeInterface;
use Sdk\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\UseCase\ConvertNetRate;
use Services\CurrencyRate\UseCase\GetNetRate;

class CurrencyRateAdapter implements CurrencyRateAdapterInterface
{
    public function getNetRate(string $country, CurrencyEnum $currency, DateTimeInterface $date = null): ?float
    {
        return app(GetNetRate::class)->execute($country, $currency, $date);
    }

    public function convertNetRate(
        int|float $price,
        CurrencyEnum $currencyFrom,
        CurrencyEnum $currencyTo,
        string $country = null,
        DateTimeInterface $date = null
    ): float {
        return app(ConvertNetRate::class)->execute($price, $currencyFrom, $currencyTo, $country, $date);
    }
}
