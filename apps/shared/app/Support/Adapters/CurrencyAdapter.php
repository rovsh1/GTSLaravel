<?php

namespace App\Shared\Support\Adapters;

use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\Dto\GetRateDto;
use Services\CurrencyRate\UseCase\GetNetRate;
use Services\CurrencyRate\UseCase\UpdateRates;
use Services\CurrencyRate\ValueObject\CountryEnum;

class CurrencyAdapter
{
    public function getRate(string $currency, string $country = null): float
    {
        return app(GetNetRate::class)->execute(
            new GetRateDto(
                $country ? CountryEnum::from($country) : CountryEnum::UZ,
                CurrencyEnum::from($currency)
            //'date' => $date
            )
        );
    }

    public function updateRates(string|\DateTime $date = null): void
    {
        app(UpdateRates::class)->execute($date);
    }

    public function updateCountryRates(string $country, string|\DateTime $date = null): void
    {
    }
}