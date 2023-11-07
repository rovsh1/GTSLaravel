<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Models\Booking;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Models\Details\Airport;
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

    public function findOrFail(BookingId $bookingId): CIPRoomInAirport
    {
        $entity = $this->find($bookingId);
        if ($entity === null) {
            throw new EntityNotFoundException('Not found booking details');
        }

        return $entity;
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
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'guestIds' => $guestIds->toData(),
            ]
        ]);

        return $this->detailsFactory->build(Airport::find($model->id));
    }

    public function store(CIPRoomInAirport $details): bool
    {
        return (bool)Airport::whereId($details->id()->value())->update([
            'date' => $details->serviceDate(),
            'booking_airport_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId()->value(),
                'flightNumber' => $details->flightNumber(),
                'guestIds' => $details->guestIds()->toData(),
            ]
        ]);
    }
}
