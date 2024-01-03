<?php

namespace Services\CurrencyRate\Contracts;

use DateTime;
use Services\CurrencyRate\ValueObject\CountryEnum;
use Services\CurrencyRate\ValueObject\CurrencyRatesCollection;

interface ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;
}