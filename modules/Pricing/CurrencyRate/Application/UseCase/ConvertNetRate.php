<?php

namespace Module\Pricing\CurrencyRate\Application\UseCase;

use DateTimeInterface;
use Module\Pricing\CurrencyRate\Domain\Service\RatioCalculator;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

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
