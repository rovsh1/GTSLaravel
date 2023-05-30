<?php

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

class AbstractRules
{
    protected array $transitions = [];

    protected function addTransition(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): void
    {
        if (!isset($this->transitions[$fromStatus->value]))
            $this->transitions[$fromStatus->value] = [];

        $this->transitions[$fromStatus->value][] = $toStatus->value;
    }

    public function canTransit(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): bool
    {
        if (!isset($this->transitions[$fromStatus->value]))
            return false;

        return in_array($toStatus->value, $this->transitions[$fromStatus->value]);
    }
}
