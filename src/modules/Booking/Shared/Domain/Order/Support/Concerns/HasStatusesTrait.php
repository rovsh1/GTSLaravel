<?php

namespace Module\Booking\Shared\Domain\Order\Support\Concerns;

use Module\Booking\Shared\Domain\Order\Event\OrderCancelled;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

trait HasStatusesTrait
{
    public function inModeration(): bool
    {
        return $this->status === OrderStatusEnum::IN_PROGRESS;
    }

    public function toInProgress(): void
    {
        $this->status = OrderStatusEnum::IN_PROGRESS;
    }

    public function toWaitingInvoice(): void
    {
        $this->status = OrderStatusEnum::WAITING_INVOICE;
    }

    public function toInvoiced(): void
    {
        $this->status = OrderStatusEnum::INVOICED;
    }

    public function toPartialPaid(): void
    {
        $this->status = OrderStatusEnum::PARTIAL_PAID;
    }

    public function toPaid(): void
    {
        $this->status = OrderStatusEnum::PAID;
    }

    public function cancel(): void
    {
        $this->status = OrderStatusEnum::CANCELLED;
        $this->pushEvent(new OrderCancelled($this));
    }

    public function toRefundFee(): void
    {
        $this->status = OrderStatusEnum::REFUND_FEE;
    }

    public function toRefundNoFee(): void
    {
        $this->status = OrderStatusEnum::REFUND_NO_FEE;
    }
}