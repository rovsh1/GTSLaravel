<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\DayCarTripFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\DayCarTripBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\BookingDetails\DayCarTrip;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

class DayCarTripFactory extends AbstractServiceDetailsFactory implements DayCarTripFactoryInterface
{
    public function __construct(private readonly DayCarTripBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        CarBidCollection $carBids,
        ?string $destinationsDescription,
        ?DateTimeInterface $departureDate,
    ): DayCarTrip {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'carBids' => $carBids->toData(),
                'cityId' => $cityId,
                'destinationsDescription' => $destinationsDescription,
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
