<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;

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
