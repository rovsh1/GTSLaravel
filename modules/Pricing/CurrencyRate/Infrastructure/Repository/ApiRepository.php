<?php

namespace Module\Pricing\CurrencyRate\Infrastructure\Repository;

use Module\Pricing\CurrencyRate\Domain\Repository\ApiRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyRatesCollection;
use Module\Pricing\CurrencyRate\Infrastructure\Api\ApiFactory;
use DateTime;

class ApiRepository implements ApiRepositoryInterface
{
    public function getCountryRates(CountryEnum $country, DateTime $date = null): CurrencyRatesCollection
    {
        return ApiFactory::fromCountry($country)->getRates($date);
    }
}