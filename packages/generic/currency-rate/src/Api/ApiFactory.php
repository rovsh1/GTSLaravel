<?php

namespace Pkg\CurrencyRate\Api;

use Pkg\CurrencyRate\ValueObject\CountryEnum;

class ApiFactory
{
    public static function fromCountry(CountryEnum $country): ApiInterface
    {
        return match ($country) {
            CountryEnum::RU => new CBRU(),
            CountryEnum::UZ => new NBU(),
            default => throw new \LogicException('CurrencyApi for country [' . $country->value . '] not implemented'),
        };
    }
}