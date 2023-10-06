<?php

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

use DateTimeInterface;

class AirportInfo implements InfoInterface
{
    private string $flightNumber;

    private DateTimeInterface $serviceDate;
}