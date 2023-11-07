<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;

interface CIPRoomInAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?CIPRoomInAirport;

    public function findOrFail(BookingId $bookingId): CIPRoomInAirport;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $serviceDate,
        GuestIdCollection $guestIds,
    ): CIPRoomInAirport;

    public function store(CIPRoomInAirport $details): bool;
}
