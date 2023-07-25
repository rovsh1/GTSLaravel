<?php

namespace Module\Generic\CurrencyRate\Application\Request;

use DateTime;
use Module\Generic\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Shared\Enum\CurrencyEnum;

class GetRateDto
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly CurrencyEnum $currency,
        public readonly ?DateTime $date = null,
    ) {
    }
}
