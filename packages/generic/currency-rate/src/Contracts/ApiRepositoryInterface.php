<?php

namespace Pkg\CurrencyRate\Contracts;

use DateTime;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Pkg\CurrencyRate\ValueObject\CurrencyRatesCollection;

interface ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;
}