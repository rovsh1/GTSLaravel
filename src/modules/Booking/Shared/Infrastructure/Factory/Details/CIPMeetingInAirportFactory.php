<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPMeetingInAirportFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\CIPMeetingInAirportBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

class CIPMeetingInAirportFactory extends AbstractServiceDetailsFactory implements CIPMeetingInAirportFactoryInterface
{
    public function __construct(private readonly CIPMeetingInAirportBuilder $builder) {}

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
            'guestIds' => $guestIds->serialize(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
            ]
        ]);

        return $this->builder->build(Airport::find($model->id));
    }
}
