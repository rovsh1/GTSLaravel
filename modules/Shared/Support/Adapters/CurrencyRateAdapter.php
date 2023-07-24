<?php

namespace Module\Shared\Support\Adapters;

use DateTimeInterface;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Support\CurrencyRate\Application\UseCase\ConvertNetRate;
use Module\Support\CurrencyRate\Application\UseCase\GetNetRate;

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
