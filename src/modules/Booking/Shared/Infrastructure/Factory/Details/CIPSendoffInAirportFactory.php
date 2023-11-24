<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPSendoffInAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Shared\Infrastructure\Builder\Details\CIPSendoffInAirportBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;

class CIPSendoffInAirportFactory extends AbstractServiceDetailsFactory implements CIPSendoffInAirportFactoryInterface
{
    public function __construct(private readonly CIPSendoffInAirportBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $departureDate,
        GuestIdCollection $guestIds,
    ): CIPSendoffInAirport {
        $model = Airport::create([
            'booking_id' => $bookingId->value(),
            'date' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'guestIds' => $guestIds->serialize(),
            ]
        ]);

        return $this->builder->build(Airport::find($model->id));
    }
}
