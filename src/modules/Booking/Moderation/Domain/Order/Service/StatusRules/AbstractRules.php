<?php

namespace Module\Booking\Moderation\Domain\Order\Service\StatusRules;


use Module\Shared\Enum\Booking\OrderStatusEnum;

class AbstractRules
{
    /**
     * @var array<int, OrderStatusEnum[]> $transitions
     */
    protected array $transitions = [];

    protected function addTransition(OrderStatusEnum $fromStatus, OrderStatusEnum $toStatus): void
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            $this->transitions[$fromStatus->value] = [];
        }

        $this->transitions[$fromStatus->value][] = $toStatus;
    }

    public function canTransit(OrderStatusEnum $fromStatus, OrderStatusEnum $toStatus): bool
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            return false;
        }

        return in_array($toStatus, $this->transitions[$fromStatus->value]);
    }

    /**
     * @param OrderStatusEnum $statusEnum
     * @return OrderStatusEnum[]
     */
    public function getStatusTransitions(OrderStatusEnum $statusEnum): array
    {
        return $this->transitions[$statusEnum->value] ?? [];
    }
}
