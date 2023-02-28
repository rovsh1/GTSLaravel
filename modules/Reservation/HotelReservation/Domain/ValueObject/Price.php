<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

class Price extends \Module\Shared\Domain\ValueObject\Price
{
    public function __construct(?float $value = null, ?string $currency)
    {
        parent::__construct($value, $currency);
    }
}
