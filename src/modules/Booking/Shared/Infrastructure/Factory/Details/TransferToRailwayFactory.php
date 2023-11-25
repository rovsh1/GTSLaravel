<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferToRailwayFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\TransferToRailwayBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\BookingDetails\TransferToRailway;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

class TransferToRailwayFactory extends AbstractServiceDetailsFactory implements TransferToRailwayFactoryInterface
{
    public function __construct(private readonly TransferToRailwayBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $railwayStationId,
        int $cityId,
        CarBidCollection $carBids,
        ?string $trainNumber,
        ?string $meetingTablet,
        ?DateTimeInterface $departureDate,
    ): TransferToRailway {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'railwayStationId' => $railwayStationId,
                'cityId' => $cityId,
                'trainNumber' => $trainNumber,
                'meetingTablet' => $meetingTablet,
                'carBids' => $carBids->toData(),
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
