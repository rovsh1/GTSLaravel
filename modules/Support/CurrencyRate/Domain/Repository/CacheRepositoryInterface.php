<?php

namespace Module\Support\CurrencyRate\Domain\Repository;

use DateTime;
use Module\Shared\Enum\CurrencyEnum;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Support\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Support\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

interface CacheRepositoryInterface
{
    public function getRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;

    public function getRate(CountryEnum $country, CurrencyEnum $currency, DateTime $date = null): ?CurrencyRate;

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void;

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null, int $ttl = null): void;
}