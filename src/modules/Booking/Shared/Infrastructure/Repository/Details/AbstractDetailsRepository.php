<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository\Details;

use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Infrastructure\Factory\DetailsFactory;

abstract class AbstractDetailsRepository
{
    public function __construct(
        protected readonly DetailsFactory $detailsFactory
    ) {}

    protected function serializeServiceInfo(ServiceInfo $serviceInfo): array
    {
        return [
            'serviceId' => $serviceInfo->id(),
            'title' => $serviceInfo->title(),
            'supplierId' => $serviceInfo->supplierId(),
        ];
    }
}
