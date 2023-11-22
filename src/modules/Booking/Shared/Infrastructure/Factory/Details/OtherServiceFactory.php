<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory\Details;

use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Domain\Booking\Factory\Details\OtherServiceFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Builder\Details\OtherServiceBuilder;
use Module\Booking\Shared\Infrastructure\Models\Details\Other as Model;

class OtherServiceFactory extends AbstractServiceDetailsFactory implements OtherServiceFactoryInterface
{
    public function __construct(private readonly OtherServiceBuilder $builder)
    {
    }

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        ?string $description,
        ?\DateTimeInterface $date,
    ): Other {
        $model = Model::create([
            'booking_id' => $bookingId->value(),
            'service_id' => $serviceInfo->id(),
            'data' => [
                'serviceInfo' => $this->serializeServiceInfo($serviceInfo),
                'description' => $description,
                'date' => $date?->getTimestamp(),
            ]
        ]);

        return $this->builder->build(Model::find($model->id));
    }
}
