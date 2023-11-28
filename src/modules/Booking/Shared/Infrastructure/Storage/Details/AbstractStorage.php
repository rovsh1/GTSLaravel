<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Storage\Details;

use Sdk\Booking\ValueObject\ServiceInfo;

abstract class AbstractStorage
{
    protected function serializeServiceInfo(ServiceInfo $serviceInfo): array
    {
        return [
            'serviceId' => $serviceInfo->id(),
            'title' => $serviceInfo->title(),
            'supplierId' => $serviceInfo->supplierId(),
        ];
    }
}