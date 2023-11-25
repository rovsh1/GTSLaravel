<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferToAirportFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\TransferToAirportBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\BookingDetails\TransferToAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

class TransferToAirportFactory extends AbstractServiceDetailsFactory implements TransferToAirportFactoryInterface
{
    public function __construct(private readonly TransferToAirportBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        CarBidCollection $carBids,
        ?string $flightNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToAirport {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'meetingTablet' => $meetingTablet,
                'carBids' => $carBids->toData(),
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
