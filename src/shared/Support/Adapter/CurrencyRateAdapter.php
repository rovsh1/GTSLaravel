<?php

namespace Shared\Support\Adapter;

use DateTimeInterface;
use Pkg\CurrencyRate\UseCase\ConvertNetRate;
use Pkg\CurrencyRate\UseCase\GetNetRate;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;

class CurrencyRateAdapter implements CurrencyRateAdapterInterface
{
    public function getNetRate(
        string|CountryEnum $country,
        CurrencyEnum $currency,
        DateTimeInterface $date = null
    ): ?float {
        if (is_string($country)) {
            $country = CountryEnum::from(strtoupper($country));
        }

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
