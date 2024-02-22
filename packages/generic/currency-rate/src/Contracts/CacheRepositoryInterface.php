<?php

namespace Pkg\CurrencyRate\Contracts;

use DateTime;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Pkg\CurrencyRate\ValueObject\CurrencyRate;
use Pkg\CurrencyRate\ValueObject\CurrencyRatesCollection;
use Sdk\Shared\Enum\CurrencyEnum;

interface CacheRepositoryInterface
{
    public function getRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;

    public function getRate(CountryEnum $country, CurrencyEnum $currency, DateTime $date = null): ?CurrencyRate;

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void;

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null, int $ttl = null): void;
}