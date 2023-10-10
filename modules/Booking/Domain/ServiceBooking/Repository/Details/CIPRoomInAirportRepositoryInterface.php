<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

interface CIPRoomInAirportRepositoryInterface
{
    public function find(BookingId $bookingId): ?CIPRoomInAirport;

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
