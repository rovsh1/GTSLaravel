<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

class Price extends \Module\Shared\Domain\ValueObject\Price
{
    public function __construct(?float $value = null, ?string $currency)
    {
        parent::__construct($value, $currency);
    }
}
