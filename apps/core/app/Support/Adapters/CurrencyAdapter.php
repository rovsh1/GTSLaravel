<?php

namespace App\Core\Support\Adapters;

use Module\Generic\CurrencyRate\Application\Request\GetRateDto;
use Module\Generic\CurrencyRate\Application\UseCase\GetNetRate;
use Module\Generic\CurrencyRate\Application\UseCase\UpdateRates;
use Module\Generic\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Shared\Enum\CurrencyEnum;

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