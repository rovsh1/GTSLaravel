<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\ServiceBooking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Infrastructure\ServiceBooking\Models\Details\Airport;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CIPRoomInAirportRepository extends AbstractDetailsRepository implements CIPRoomInAirportRepositoryInterface
{
    public function find(BookingId $bookingId): CIPRoomInAirport
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->detailsFactory->buildByBooking($booking);
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $serviceDate,
        GuestIdCollection $guestIds,
    ): CIPRoomInAirport {
        $model = Airport::create([
            'booking_id' => $bookingId->value(),
            'date' => $serviceDate,
            'service_id' => $serviceInfo->id()->value(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'guestIds' => $guestIds->toData(),
            ]
        ]);

        return $this->detailsFactory->build(Airport::find($model->id));
    }
}
