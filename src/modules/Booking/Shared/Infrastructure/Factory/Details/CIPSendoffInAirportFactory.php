<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPSendoffInAirportFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\CIPSendoffInAirportBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Airport;
use Sdk\Booking\Entity\Details\CIPSendoffInAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

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
