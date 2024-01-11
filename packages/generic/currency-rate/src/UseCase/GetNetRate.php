<?php

namespace Pkg\CurrencyRate\UseCase;

use DateTimeInterface;
use Exception;
use Pkg\CurrencyRate\Service\RateManager;
use Pkg\CurrencyRate\ValueObject\CountryEnum;
use Sdk\Shared\Enum\CurrencyEnum;

class GetNetRate
{
    public function __construct(
        private readonly RateManager $rateCalculator
    ) {}

    /**
     * @throws Exception
     */
    public function execute(
        CountryEnum $country,
        CurrencyEnum $currency,
        ?DateTimeInterface $date = null
    ): ?float {
        $rate = $this->rateCalculator->get($country, $currency, $date);

        return $rate?->rate();
    }
}
