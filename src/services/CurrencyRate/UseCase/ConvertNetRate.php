<?php

namespace Services\CurrencyRate\UseCase;

use DateTimeInterface;
use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\Service\RatioCalculator;
use Services\CurrencyRate\ValueObject\CountryEnum;

class ConvertNetRate
{
    private RatioCalculator $ratioCalculator;

    public function __construct(
        RatioCalculator $ratioCalculator
    ) {
        $this->ratioCalculator = $ratioCalculator;
    }

    public function execute(
        int|float $price,
        CurrencyEnum $currencyFrom,
        CurrencyEnum $currencyTo,
        string $country,
        DateTimeInterface $date = null
    ): float {
        $ratio = $this->ratioCalculator->getNetRatio(
            $currencyFrom,
            $currencyTo,
            CountryEnum::from($country),
            $date
        );

        return $price * $ratio;
    }
}
