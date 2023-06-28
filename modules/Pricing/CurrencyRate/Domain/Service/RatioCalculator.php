<?php

declare(strict_types=1);

namespace Module\Pricing\CurrencyRate\Domain\Service;

use DateTime;
use DateTimeInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Shared\Enum\CurrencyEnum;

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
