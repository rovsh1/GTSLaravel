<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferFromAirportFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\TransferFromAirportBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\TransferFromAirport;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

class TransferFromAirportFactory extends AbstractServiceDetailsFactory implements TransferFromAirportFactoryInterface
{
    public function __construct(private readonly TransferFromAirportBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $airportId,
        ?string $flightNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromAirport {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $arrivalDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'airportId' => $airportId,
                'flightNumber' => $flightNumber,
                'meetingTablet' => $meetingTablet,
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
