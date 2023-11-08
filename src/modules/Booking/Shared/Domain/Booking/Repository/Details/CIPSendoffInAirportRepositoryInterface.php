<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;

interface CIPSendoffInAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?CIPSendoffInAirport;

    public function findOrFail(BookingId $bookingId): CIPSendoffInAirport;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $departureDate,
        GuestIdCollection $guestIds,
    ): CIPSendoffInAirport;

    public function store(CIPSendoffInAirport $details): bool;
}
