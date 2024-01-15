<?php

namespace Pkg\CurrencyRate\UseCase;

use DateTimeInterface;
use Pkg\CurrencyRate\Service\RatioCalculator;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\CurrencyEnum;

class ConvertNetRate implements UseCaseInterface
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
