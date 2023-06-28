<?php

namespace Module\Shared\Domain\Adapter;

use DateTimeInterface;
use Module\Shared\Enum\CurrencyEnum;

interface CurrencyRateAdapterInterface
{
    public function getNetRate(string $country, CurrencyEnum $currency, DateTimeInterface $date = null): ?float;

    public function convertNetRate(
        int|float $price,
        CurrencyEnum $currencyFrom,
        CurrencyEnum $currencyTo,
        string $country = null,
        DateTimeInterface $date = null
    ): float;
}
