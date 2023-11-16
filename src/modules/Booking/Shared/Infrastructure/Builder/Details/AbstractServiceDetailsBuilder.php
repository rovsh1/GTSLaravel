<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Builder\Details;

use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;

abstract class AbstractServiceDetailsBuilder
{
    protected function buildServiceInfo(array $data): ServiceInfo
    {
        return new ServiceInfo(
            $data['serviceId'],
            $data['title'],
            $data['supplierId'],
        );
    }
}
