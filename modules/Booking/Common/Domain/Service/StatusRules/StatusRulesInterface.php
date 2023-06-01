<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

interface StatusRulesInterface
{
    public function canTransit(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): bool;
}
