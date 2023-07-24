<?php

namespace Module\Support\CurrencyRate\Domain\Repository;

use DateTime;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Support\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

interface ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;
}