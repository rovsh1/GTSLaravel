<?php

namespace Module\Pricing\CurrencyRate\Application\Command;

use DateTime;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CountryEnum;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\CommandInterface;

class GetRate implements CommandInterface
{
    public function __construct(
        public readonly CountryEnum $country,
        public readonly CurrencyEnum $currency,
        public readonly ?DateTime $date = null,
    ) {
    }
}
