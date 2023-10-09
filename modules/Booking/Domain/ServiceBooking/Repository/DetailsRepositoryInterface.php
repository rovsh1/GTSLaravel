<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Repository;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;

interface DetailsRepositoryInterface
{
    public function find(BookingId $bookingId): ?ServiceDetailsInterface;

    public function createTransferToAirport(
        BookingId $bookingId,
        int $airportId,
        string $flightNumber,
        DateTimeInterface $departureDate,
    ): TransferToAirport;

    public function createTransferFromAirport(
        BookingId $bookingId,
        int $airportId,
        string $flightNumber,
        DateTimeInterface $arrivalDate,
        string $meetingTablet
    ): TransferFromAirport;
}
