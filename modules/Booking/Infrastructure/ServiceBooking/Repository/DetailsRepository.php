<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsFactory;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;

class DetailsRepository implements DetailsRepositoryInterface
{
    public function __construct(
        private readonly DetailsFactory $detailsFactory
    ) {}

    public function find(BookingId $bookingId): ?ServiceDetailsInterface
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            return null;
        }

        return $this->detailsFactory->build($booking);
    }

    public function createTransferToAirport(
        BookingId $bookingId,
        int $airportId,
        string $flightNumber,
        DateTimeInterface $departureDate,
    ): TransferToAirport {
        // TODO: Implement createTransferToAirport() method.
    }

    public function createTransferFromAirport(
        BookingId $bookingId,
        int $airportId,
        string $flightNumber,
        DateTimeInterface $arrivalDate,
        string $meetingTablet
    ): TransferFromAirport {
        // TODO: Implement createTransferFromAirport() method.
    }
}
