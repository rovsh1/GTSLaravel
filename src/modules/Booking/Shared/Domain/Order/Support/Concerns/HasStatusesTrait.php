<?php

namespace Module\Booking\Shared\Domain\Order\Support\Concerns;

use Module\Booking\Moderation\Domain\Order\Exception\RefundFeeAmountBelowOrEqualZero;
use Module\Booking\Shared\Domain\Order\Event\OrderCancelled;
use Module\Booking\Shared\Domain\Order\Event\OrderRefunded;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\ValueObject\Money;

trait HasStatusesTrait
{
    public function inModeration(): bool
    {
        return $this->status === OrderStatusEnum::IN_PROGRESS || $this->isWaitingInvoice() || $this->isInvoiced();
    }

    public function isWaitingInvoice(): bool
    {
        return $this->status === OrderStatusEnum::WAITING_INVOICE;
    }

    public function isInvoiced(): bool
    {
        return $this->status === OrderStatusEnum::INVOICED;
    }

    public function canCreateVoucher(): bool
    {
        return in_array($this->status, [
            OrderStatusEnum::WAITING_INVOICE,
            OrderStatusEnum::INVOICED,
            OrderStatusEnum::PARTIAL_PAID,
            OrderStatusEnum::PAID,
        ]);
    }

    public function canSendVoucher(): bool
    {
        return $this->canCreateVoucher();
    }

    public function canCreateInvoice(): bool
    {
        return $this->isWaitingInvoice() || $this->status === OrderStatusEnum::REFUND_FEE;
    }

    public function canSendInvoice(): bool
    {
        return $this->isInvoiced() || $this->status === OrderStatusEnum::REFUND_FEE;
    }

    public function canCancelInvoice(): bool
    {
        return $this->isInvoiced();
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

    public function toRefundFee(float $refundFeeAmount): void
    {
        if ($refundFeeAmount <= 0) {
            throw new RefundFeeAmountBelowOrEqualZero();
        }
        $this->status = OrderStatusEnum::REFUND_FEE;
        $this->clientPenalty = new Money($this->clientPrice->currency(), $refundFeeAmount);
        $this->pushEvent(new OrderRefunded($this));
    }

    public function toRefundNoFee(): void
    {
        $this->status = OrderStatusEnum::REFUND_NO_FEE;
        $this->pushEvent(new OrderRefunded($this));
    }
}
