<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPMeetingInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Shared\Infrastructure\Factory\Details\CIPMeetingInAirportFactory;
use Module\Booking\Shared\Infrastructure\Models\Booking;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CIPMeetingInAirportRepository extends AbstractServiceDetailsRepository
    implements CIPMeetingInAirportRepositoryInterface
{
    public function find(BookingId $bookingId): CIPMeetingInAirport
    {
        $booking = Booking::find($bookingId->value());
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        return $this->build($booking->airportDetails);
    }

    public function findOrFail(BookingId $bookingId): CIPMeetingInAirport
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
        ?DateTimeInterface $arrivalDate,
        GuestIdCollection $guestIds,
    ): CIPMeetingInAirport {
        $model = Airport::create([
            'booking_id' => $bookingId->value(),
            'date' => $arrivalDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'guestIds' => $guestIds->toData(),
            ]
        ]);

        return $this->build(Airport::find($model->id));
    }

    public function store(CIPMeetingInAirport $details): bool
    {
        return (bool)Airport::whereId($details->id()->value())->update([
            'date' => $details->arrivalDate(),
            'booking_airport_details.data' => [
                'serviceInfo' => $this->serializeServiceInfo($details->serviceInfo()),
                'airportId' => $details->airportId()->value(),
                'flightNumber' => $details->flightNumber(),
                'guestIds' => $details->guestIds()->toData(),
            ]
        ]);
    }

    private function build(Airport $details): CIPMeetingInAirport
    {
        return (new CIPMeetingInAirportFactory())->build($details);
    }
}
