<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Support\Concerns;

use Module\Booking\Shared\Domain\Booking\Event\Status\BookingCancelled;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingCancelledFee;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingCancelledNoFee;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingConfirmed;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingNotConfirmed;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingProcessing;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingWaitingCancellation;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingWaitingConfirmation;
use Module\Booking\Shared\Domain\Booking\Event\Status\BookingWaitingProcessing;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

trait HasStatusesTrait
{
    public function status(): BookingStatusEnum
    {
        return $this->status;
    }

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
        //@todo тут нужно исправление
//        if ($this->type() === BookingTypeEnum::HOTEL && empty($reason)) {
//            throw new \InvalidArgumentException('Not confirmed reason can\'t be empty');
//        }
        $this->setStatus(BookingStatusEnum::NOT_CONFIRMED);
        $this->pushEvent(new BookingNotConfirmed($this, $reason));
    }

    public function toCancelledNoFee(): void
    {
        $this->setStatus(BookingStatusEnum::CANCELLED_NO_FEE);
        $this->pushEvent(new BookingCancelledNoFee($this));
    }

    /**
     * @param float $supplierPenalty
     * @param float|null $clientPenalty
     * @return void
     * @throws \InvalidArgumentException
     */
    public function toCancelledFee(float $supplierPenalty, ?float $clientPenalty = null): void
    {
        if ($supplierPenalty <= 0) {
            throw new \InvalidArgumentException('Cancel fee amount can\'t be below zero');
        }
        $this->setStatus(BookingStatusEnum::CANCELLED_FEE);

        $newSupplierPrice = new BookingPriceItem(
            $this->prices()->supplierPrice()->currency(),
            $this->prices()->supplierPrice()->calculatedValue(),
            $this->prices()->supplierPrice()->manualValue(),
            $supplierPenalty,
        );
        $newClientPrice = new BookingPriceItem(
            $this->prices()->clientPrice()->currency(),
            $this->prices()->clientPrice()->calculatedValue(),
            $this->prices()->clientPrice()->manualValue(),
            $clientPenalty,
        );

        $this->updatePrice(new BookingPrices($newSupplierPrice, $newClientPrice));
        $this->pushEvent(new BookingCancelledFee($this, $supplierPenalty));
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

    protected function setStatus(BookingStatusEnum $status): void
    {
        $this->status = $status;
    }
}
