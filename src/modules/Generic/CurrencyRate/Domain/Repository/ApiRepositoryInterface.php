<?php

namespace Module\Generic\CurrencyRate\Domain\Repository;

use DateTime;
use Module\Generic\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;

interface ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection;
}