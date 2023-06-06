<?php

namespace Module\Pricing\CurrencyRate\Application\Request;

use DateTime;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;

class GetRateDto
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly CurrencyEnum $currency,
        public readonly ?DateTime $date = null,
    ) {
    }
}
