<?php

namespace App\Shared\Support\Adapters;

use Sdk\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\ValueObject\CountryEnum;

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