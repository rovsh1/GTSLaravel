<?php

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

use DateTimeInterface;

class MeetingAtAirportInfo implements InfoInterface
{
    private string $flightNumber;

    private DateTimeInterface $arrivalDate;

    private string $meetingTablet;
}