<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPMeetingInAirport;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPMeetingInAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Shared\Infrastructure\Builder\Details\CIPMeetingInAirportBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;

class CIPMeetingInAirportFactory extends AbstractServiceDetailsFactory implements CIPMeetingInAirportFactoryInterface
{
    public function __construct(private readonly CIPMeetingInAirportBuilder $builder)
    {
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

        return $this->builder->build(Airport::find($model->id));
    }
}
