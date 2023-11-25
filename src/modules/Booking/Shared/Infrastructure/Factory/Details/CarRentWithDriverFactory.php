<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Factory\Details\CarRentWithDriverFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\CarRentWithDriverBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\BookingDetails\CarRentWithDriver;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPeriod;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

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
