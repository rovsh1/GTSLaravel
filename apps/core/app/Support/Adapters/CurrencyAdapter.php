<?php

namespace App\Core\Support\Adapters;

use Module\Shared\Enum\CurrencyEnum;
use Module\Support\CurrencyRate\Application\Request\GetRateDto;
use Module\Support\CurrencyRate\Application\UseCase\GetNetRate;
use Module\Support\CurrencyRate\Application\UseCase\UpdateRates;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;

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