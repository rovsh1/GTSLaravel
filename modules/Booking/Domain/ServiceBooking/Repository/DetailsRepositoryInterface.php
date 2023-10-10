<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Repository;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;

interface DetailsRepositoryInterface
{
    public function find(BookingId $bookingId): ?ServiceDetailsInterface;

    public function createTransferToAirport(
        BookingId $bookingId,
        string $serviceTitle,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $departureDate,
    ): TransferToAirport;

    public function createTransferFromAirport(
        BookingId $bookingId,
        string $serviceTitle,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport;

    public function createCIPRoomInAirport(
        BookingId $bookingId,
        string $serviceTitle,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $serviceDate,
        GuestIdCollection $guestIds,
    ): CIPRoomInAirport;
}
