<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Service;

use Module\Shared\Enum\Booking\OrderStatusEnum;

interface OrderStatusStorageInterface
{
    public function getColor(OrderStatusEnum $status): ?string;

    public function getName(OrderStatusEnum $status): string;
}
