<?php

namespace Module\Pricing\CurrencyRate\Domain\Repository;

use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use DateTime;

interface ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;
}