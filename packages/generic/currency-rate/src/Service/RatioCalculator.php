<?php

declare(strict_types=1);

namespace Pkg\CurrencyRate\Service;

use DateTime;
use DateTimeInterface;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Sdk\Shared\Enum\CurrencyEnum;

class RatioCalculator
{
    public function __construct(
        private readonly RateManager $rateManager
    ) {
    }

    public function getNetRatio(
        CurrencyEnum $currencyFrom,
        CurrencyEnum $currencyTo,
        CountryEnum $country,
        DateTimeInterface $date = null
    ): float {
        if ($currencyFrom->value === $currencyTo->value) {
            return 1.0;
        }

        $date = $date ?? new DateTime();
        $rateFrom = $this->rateManager->get($country, $currencyFrom, $date);
        $rateTo = $this->rateManager->get($country, $currencyTo, $date);

        return $rateFrom->rate() / $rateTo->rate();
    }
}
