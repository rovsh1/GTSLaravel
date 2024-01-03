<?php

namespace Services\CurrencyRate\Dto;

use DateTime;
use Sdk\Shared\Enum\CurrencyEnum;
use Services\CurrencyRate\ValueObject\CountryEnum;

class GetRateDto
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly CurrencyEnum $currency,
        public readonly ?DateTime $date = null,
    ) {
    }
}
