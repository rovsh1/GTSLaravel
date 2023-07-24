<?php

namespace Module\Support\CurrencyRate\Infrastructure\Repository;

use DateTime;
use Module\Support\CurrencyRate\Domain\Repository\ApiRepositoryInterface;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Support\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Support\CurrencyRate\Infrastructure\Api\ApiFactory;

class ApiRepository implements ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return ApiFactory::fromCountry($country)->getRates($date);
    }
}