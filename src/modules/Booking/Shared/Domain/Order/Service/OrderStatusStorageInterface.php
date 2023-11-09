<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Service;

use Module\Shared\Enum\Order\OrderStatusEnum;

interface OrderStatusStorageInterface
{
    public function getColor(OrderStatusEnum $status): ?string;

    public function getName(OrderStatusEnum $status): string;
}
