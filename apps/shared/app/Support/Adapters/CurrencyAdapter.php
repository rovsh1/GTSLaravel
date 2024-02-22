<?php

namespace App\Shared\Support\Adapters;

use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;

class CurrencyAdapter
{
    public function __construct(
        private readonly CurrencyRateAdapterInterface $baseAdapter
    ) {}

    public function getRate(string $currency, string $country = null): float
    {
        return $this->baseAdapter->getNetRate(
            $country ?? CountryEnum::UZ->value,
            CurrencyEnum::from($currency)
        //'date' => $date
        );
    }
}