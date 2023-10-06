<?php

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

use DateTimeInterface;

class AirportDepartureInfo implements InfoInterface
{
    private string $flightNumber;

    private DateTimeInterface $departureDate;
}