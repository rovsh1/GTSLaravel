<?php

namespace Pkg\CurrencyRate\Repository;

use DateTime;
use Pkg\CurrencyRate\Api\ApiFactory;
use Pkg\CurrencyRate\Contracts\ApiRepositoryInterface;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Pkg\CurrencyRate\ValueObject\CurrencyRatesCollection;

class ApiRepository implements ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return ApiFactory::fromCountry($country)->getRates($date);
    }
}