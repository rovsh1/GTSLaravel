<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use DateTimeInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\IntercityTransferFactoryInterface;
use Module\Booking\Shared\Infrastructure\Builder\Details\IntercityTransferBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Transfer;
use Sdk\Booking\Entity\Details\IntercityTransfer;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\ServiceInfo;

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
        bool $returnTripIncluded,
        ?DateTimeInterface $departureDate,
    ): IntercityTransfer {
        $model = Transfer::create([
            'booking_id' => $bookingId->value(),
            'date_start' => $departureDate,
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'fromCityId' => $fromCityId,
                'toCityId' => $toCityId,
                'returnTripIncluded' => $returnTripIncluded,
            ]
        ]);

        return $this->builder->build(Transfer::find($model->id));
    }
}
