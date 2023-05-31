<?php

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

class AbstractRules
{
    /**
     * @var array<int, BookingStatusEnum[]> $transitions
     */
    protected array $transitions = [];

    protected function addTransition(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): void
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            $this->transitions[$fromStatus->value] = [];
        }

        $this->transitions[$fromStatus->value][] = $toStatus;
    }

    public function canTransit(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): bool
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            return false;
        }

        return in_array($toStatus, $this->transitions[$fromStatus->value]);
    }

    /**
     * @param BookingStatusEnum $statusEnum
     * @return BookingStatusEnum[]
     */
    public function getStatusTransitions(BookingStatusEnum $statusEnum): array
    {
        return $this->transitions[$statusEnum->value] ?? [];
    }
}
