<?php

namespace Module\Generic\CurrencyRate\Domain\Repository;

use DateTime;
use Module\Generic\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRate;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Shared\Enum\CurrencyEnum;

interface CacheRepositoryInterface
{
    public function getRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;

    public function getRate(CountryEnum $country, CurrencyEnum $currency, DateTime $date = null): ?CurrencyRate;

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void;

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null, int $ttl = null): void;
}