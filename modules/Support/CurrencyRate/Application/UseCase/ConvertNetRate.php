<?php

namespace Module\Support\CurrencyRate\Application\UseCase;

use DateTimeInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Support\CurrencyRate\Domain\Service\RatioCalculator;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;
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
