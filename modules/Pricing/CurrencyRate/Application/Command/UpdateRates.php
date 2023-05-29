<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use DateTime;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateRates implements CommandInterface
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly ?DateTime $date = null,
    ) {
    }
}
