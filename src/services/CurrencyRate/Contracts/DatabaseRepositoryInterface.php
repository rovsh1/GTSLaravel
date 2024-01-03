<?php

namespace Services\CurrencyRate\Contracts;

use DateTime;
use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\ValueObject\CountryEnum;
use Services\CurrencyRate\ValueObject\CurrencyRate;
use Services\CurrencyRate\ValueObject\CurrencyRatesCollection;

interface DatabaseRepositoryInterface
{
    public function getRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;

    public function getLastFilledRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;

    public function getRate(CountryEnum $country, CurrencyEnum $currency, DateTime $date = null): ?CurrencyRate;

    public function getLastFilledRate(
        CountryEnum $country,
        CurrencyEnum $currency,
        DateTime $date = null
    ): ?CurrencyRate;

    public function getLastFilledDate(CountryEnum $country): DateTime;

    public function setRates(CountryEnum $country, CurrencyRatesCollection $rates, DateTime $date = null): void;

    public function setRate(CountryEnum $country, CurrencyRate $rate, DateTime $date = null): void;
}