<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CarRentWithDriverFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Builder\Details\CarRentWithDriverBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class CarRentWithDriverFactory extends AbstractServiceDetailsFactory implements CarRentWithDriverFactoryInterface
{
    public function __construct(private readonly CarRentWithDriverBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        CarBidCollection $carBids,
        ?BookingPeriod $bookingPeriod,
    ): CarRentWithDriver {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $bookingPeriod?->dateFrom(),
            'date_end' => $bookingPeriod?->dateTo(),
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'cityId' => $cityId,
                'carBids' => $carBids->toData(),
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
