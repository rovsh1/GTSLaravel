<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\StatusRules;

use Module\Shared\Enum\Order\OrderStatusEnum;

interface StatusRulesInterface
{
    public function canTransit(OrderStatusEnum $fromStatus, OrderStatusEnum $toStatus): bool;

    public function isEditableStatus(OrderStatusEnum $status): bool;
}
