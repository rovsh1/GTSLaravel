<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferFromRailwayFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\TransferFromRailwayBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\TransferFromRailway;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

class TransferFromRailwayFactory extends AbstractServiceDetailsFactory implements TransferFromRailwayFactoryInterface
{
    public function __construct(private readonly TransferFromRailwayBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $railwayStationId,
        int $cityId,
        ?string $trainNumber,
        ?DateTimeInterface $arrivalDate,
        ?string $meetingTablet
    ): TransferFromRailway {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $arrivalDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'railwayStationId' => $railwayStationId,
                'cityId' => $cityId,
                'trainNumber' => $trainNumber,
                'meetingTablet' => $meetingTablet,
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
