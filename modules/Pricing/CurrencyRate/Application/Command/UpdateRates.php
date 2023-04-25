<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use DateTime;

class UpdateRates implements CommandInterface
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly ?DateTime $date = null,
    ) {
    }
}
