<?php

namespace Module\Support\CurrencyRate\Application\Request;

use DateTime;
use Module\Shared\Enum\CurrencyEnum;
use Module\Support\CurrencyRate\Domain\ValueObject\CountryEnum;

class GetRateDto
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly CurrencyEnum $currency,
        public readonly ?DateTime $date = null,
    ) {
    }
}
