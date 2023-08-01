<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity\Concerns;

use Module\Booking\Common\Domain\Event\Status\BookingCancelled;
use Module\Booking\Common\Domain\Event\Status\BookingCancelledFee;
use Module\Booking\Common\Domain\Event\Status\BookingCancelledNoFee;
use Module\Booking\Common\Domain\Event\Status\BookingConfirmed;
use Module\Booking\Common\Domain\Event\Status\BookingInvoiced;
use Module\Booking\Common\Domain\Event\Status\BookingNotConfirmed;
use Module\Booking\Common\Domain\Event\Status\BookingPaid;
use Module\Booking\Common\Domain\Event\Status\BookingPartiallyPaid;
use Module\Booking\Common\Domain\Event\Status\BookingProcessing;
use Module\Booking\Common\Domain\Event\Status\BookingRefundFee;
use Module\Booking\Common\Domain\Event\Status\BookingRefundNoFee;
use Module\Booking\Common\Domain\Event\Status\BookingWaitingCancellation;
use Module\Booking\Common\Domain\Event\Status\BookingWaitingConfirmation;
use Module\Booking\Common\Domain\Event\Status\BookingWaitingProcessing;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

trait HasStatusesTrait
{
    public function toProcessing(): void
    {
        $this->setStatus(BookingStatusEnum::PROCESSING);
        $this->pushEvent(new BookingProcessing($this));
    }

    public function cancel(): void
    {
        $this->setStatus(BookingStatusEnum::CANCELLED);
        $this->pushEvent(new BookingCancelled($this));
    }

    public function confirm(): void
    {
        $this->setStatus(BookingStatusEnum::CONFIRMED);
        $this->pushEvent(new BookingConfirmed($this));
    }

    /**
     * @param string $reason
     * @return void
     * @throws \InvalidArgumentException
     */
    public function toNotConfirmed(string $reason): void
    {
        if (empty($reason)) {
            throw new \InvalidArgumentException('Not confirmed reason can\'t be empty');
        }
        $this->setStatus(BookingStatusEnum::NOT_CONFIRMED);
        $this->pushEvent(new BookingNotConfirmed($this, $reason));
    }

    public function toInvoiced(): void
    {
        $this->setStatus(BookingStatusEnum::INVOICED);
        $this->pushEvent(new BookingInvoiced($this));
    }

    public function toPaid(): void
    {
        $this->setStatus(BookingStatusEnum::PAID);
        $this->pushEvent(new BookingPaid($this));
    }

    public function toPartiallyPaid(): void
    {
        $this->setStatus(BookingStatusEnum::PARTIALLY_PAID);
        $this->pushEvent(new BookingPartiallyPaid($this));
    }

    public function toCancelledNoFee(): void
    {
        $this->setStatus(BookingStatusEnum::CANCELLED_NO_FEE);
        $this->pushEvent(new BookingCancelledNoFee($this));
    }

    /**
     * @param float $cancelFeeAmount
     * @return void
     * @throws \InvalidArgumentException
     */
    public function toCancelledFee(float $cancelFeeAmount): void
    {
        if ($cancelFeeAmount <= 0) {
            throw new \InvalidArgumentException('Cancel fee amount can\'t be below zero');
        }
        $this->setStatus(BookingStatusEnum::CANCELLED_FEE);
        $this->setHoPenalty($cancelFeeAmount);
        $this->pushEvent(new BookingCancelledFee($this, $cancelFeeAmount));
    }

    public function toRefundNoFee(): void
    {
        $this->setStatus(BookingStatusEnum::REFUND_NO_FEE);
        $this->pushEvent(new BookingRefundNoFee($this));
    }

    public function toRefundFee(): void
    {
        $this->setStatus(BookingStatusEnum::REFUND_FEE);
        $this->pushEvent(new BookingRefundFee($this));
    }

    public function toWaitingConfirmation(): void
    {
        $this->setStatus(BookingStatusEnum::WAITING_CONFIRMATION);
        $this->pushEvent(new BookingWaitingConfirmation($this));
    }

    public function toWaitingCancellation(): void
    {
        $this->setStatus(BookingStatusEnum::WAITING_CANCELLATION);
        $this->pushEvent(new BookingWaitingCancellation($this));
    }

    public function toWaitingProcessing(): void
    {
        $this->setStatus(BookingStatusEnum::WAITING_PROCESSING);
        $this->pushEvent(new BookingWaitingProcessing($this));
    }
}
