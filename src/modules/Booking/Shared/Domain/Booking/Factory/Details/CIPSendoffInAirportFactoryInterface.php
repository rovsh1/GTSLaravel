<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

interface CIPSendoffInAirportFactoryInterface
{

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $departureDate,
        GuestIdCollection $guestIds,
    ): CIPSendoffInAirport;
}
