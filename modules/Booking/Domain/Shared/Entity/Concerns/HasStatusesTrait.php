<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Entity\Concerns;

use Module\Booking\Domain\Shared\Event\Status\BookingCancelled;
use Module\Booking\Domain\Shared\Event\Status\BookingCancelledFee;
use Module\Booking\Domain\Shared\Event\Status\BookingCancelledNoFee;
use Module\Booking\Domain\Shared\Event\Status\BookingConfirmed;
use Module\Booking\Domain\Shared\Event\Status\BookingNotConfirmed;
use Module\Booking\Domain\Shared\Event\Status\BookingProcessing;
use Module\Booking\Domain\Shared\Event\Status\BookingWaitingCancellation;
use Module\Booking\Domain\Shared\Event\Status\BookingWaitingConfirmation;
use Module\Booking\Domain\Shared\Event\Status\BookingWaitingProcessing;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;

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
        if ($this->type() === BookingTypeEnum::HOTEL && empty($reason)) {
            throw new \InvalidArgumentException('Not confirmed reason can\'t be empty');
        }
        $this->setStatus(BookingStatusEnum::NOT_CONFIRMED);
        $this->pushEvent(new BookingNotConfirmed($this, $reason));
    }

    public function toCancelledNoFee(): void
    {
        $this->setStatus(BookingStatusEnum::CANCELLED_NO_FEE);
        $this->pushEvent(new BookingCancelledNoFee($this));
    }

    /**
     * @param float $netPenalty
     * @param float|null $grossPenalty
     * @return void
     * @throws \InvalidArgumentException
     */
    public function toCancelledFee(float $netPenalty, ?float $grossPenalty = null): void
    {
        if ($netPenalty <= 0) {
            throw new \InvalidArgumentException('Cancel fee amount can\'t be below zero');
        }
        $this->setStatus(BookingStatusEnum::CANCELLED_FEE);
        $this->setNetPenalty($netPenalty);
        $this->setGrossPenalty($grossPenalty);
        $this->pushEvent(new BookingCancelledFee($this, $netPenalty));
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
