<?php

namespace Module\Booking\Moderation\Domain\Order\Service;

use Sdk\Shared\Enum\Order\OrderStatusEnum;

class StatusTransitionRules
{
    /**
     * @var array<int, OrderStatusEnum[]> $transitions
     */
    protected array $transitions = [];

    public function __construct()
    {
        $this->addTransition(OrderStatusEnum::IN_PROGRESS, OrderStatusEnum::WAITING_INVOICE);
        $this->addTransition(OrderStatusEnum::IN_PROGRESS, OrderStatusEnum::CANCELLED);

        $this->addTransition(OrderStatusEnum::WAITING_INVOICE, OrderStatusEnum::IN_PROGRESS);

        $this->addTransition(OrderStatusEnum::INVOICED, OrderStatusEnum::CANCELLED);

        $this->addTransition(OrderStatusEnum::PAID, OrderStatusEnum::REFUND_FEE);
        $this->addTransition(OrderStatusEnum::PAID, OrderStatusEnum::REFUND_NO_FEE);
        $this->addTransition(OrderStatusEnum::PARTIAL_PAID, OrderStatusEnum::REFUND_FEE);
        $this->addTransition(OrderStatusEnum::PARTIAL_PAID, OrderStatusEnum::REFUND_NO_FEE);
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
    public function getAvailableTransitions(OrderStatusEnum $statusEnum): array
    {
        return $this->transitions[$statusEnum->value] ?? [];
    }

    protected function addTransition(OrderStatusEnum $fromStatus, OrderStatusEnum $toStatus): void
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            $this->transitions[$fromStatus->value] = [];
        }

        $this->transitions[$fromStatus->value][] = $toStatus;
    }
}
