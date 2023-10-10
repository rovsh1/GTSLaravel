<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsFactory;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Airport;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Transfer;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class DetailsRepository implements DetailsRepositoryInterface
{
    public function __construct(
        private readonly DetailsFactory $detailsFactory
    ) {}

    public function find(BookingId $bookingId): ServiceDetailsInterface
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->detailsFactory->buildByBooking($booking);
    }

    public function createTransferToAirport(
        BookingId $bookingId,
        string $serviceTitle,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $departureDate,
    ): TransferToAirport {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'data' => [
                'serviceTitle' => $serviceTitle,
                'airportId' => $airportId,
                'flightNumber' => $flightNumber
            ]
        ]);

        return $this->detailsFactory->build(Transfer::find($model->id));
    }

    public function createTransferFromAirport(
        BookingId $bookingId,
        string $serviceTitle,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $arrivalDate,
            'data' => [
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'meetingTablet' => $meetingTablet,
                'serviceTitle' => $serviceTitle,
            ]
        ]);

        return $this->detailsFactory->build($model);
    }

    public function createCIPRoomInAirport(
        BookingId $bookingId,
        string $serviceTitle,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $serviceDate,
        GuestIdCollection $guestIds,
    ): CIPRoomInAirport {
        $model = Airport::create([
            'booking_id' => $bookingId->value(),
            'date' => $serviceDate,
            'data' => [
                'serviceTitle' => $serviceTitle,
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'guestIds' => $guestIds->toData(),
            ]
        ]);

        return $this->detailsFactory->build($model);
    }
}
