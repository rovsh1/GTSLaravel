<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Entity\IntercityTransfer;
use Module\Booking\Shared\Domain\Booking\Factory\Details\IntercityTransferFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Builder\Details\IntercityTransferBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;

class IntercityTransferFactory extends AbstractServiceDetailsFactory implements IntercityTransferFactoryInterface
{
    public function __construct(private readonly IntercityTransferBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $fromCityId,
        int $toCityId,
        CarBidCollection $carBids,
        bool $returnTripIncluded,
        ?DateTimeInterface $departureDate,
    ): IntercityTransfer {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'carBids' => $carBids->toData(),
                'fromCityId' => $fromCityId,
                'toCityId' => $toCityId,
                'returnTripIncluded' => $returnTripIncluded,
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
