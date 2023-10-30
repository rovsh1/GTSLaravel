<?php

namespace Module\Generic\CurrencyRate\Infrastructure\Repository;

use DateTime;
use Module\Generic\CurrencyRate\Domain\Repository\ApiRepositoryInterface;
use Module\Generic\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Generic\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Generic\CurrencyRate\Infrastructure\Api\ApiFactory;

class ApiRepository implements ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return ApiFactory::fromCountry($country)->getRates($date);
    }
}