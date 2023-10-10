<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Repository\Details;

use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Booking\Infrastructure\ServiceBooking\Factory\DetailsFactory;

abstract class AbstractDetailsRepository
{
    public function __construct(
        protected readonly DetailsFactory $detailsFactory
    ) {}

    protected function serializeServiceInfo(ServiceInfo $serviceInfo): array
    {
        return [
            'serviceId' => $serviceInfo->id()->value(),
            'title' => $serviceInfo->title(),
            'type' => $serviceInfo->type()->value
        ];
    }
}
