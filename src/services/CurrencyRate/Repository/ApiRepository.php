<?php

namespace Services\CurrencyRate\Repository;

use DateTime;
use Services\CurrencyRate\Api\ApiFactory;
use Services\CurrencyRate\Contracts\ApiRepositoryInterface;
use Services\CurrencyRate\ValueObject\CountryEnum;
use Services\CurrencyRate\ValueObject\CurrencyRatesCollection;

class ApiRepository implements ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return ApiFactory::fromCountry($country)->getRates($date);
    }
}