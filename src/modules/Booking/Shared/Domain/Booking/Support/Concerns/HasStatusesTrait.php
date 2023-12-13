<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Support\Concerns;

use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\Event\Status\BookingCancelled;
use Sdk\Booking\Event\Status\BookingCancelledFee;
use Sdk\Booking\Event\Status\BookingCancelledNoFee;
use Sdk\Booking\Event\Status\BookingConfirmed;
use Sdk\Booking\Event\Status\BookingNotConfirmed;
use Sdk\Booking\Event\Status\BookingProcessing;
use Sdk\Booking\Event\Status\BookingWaitingCancellation;
use Sdk\Booking\Event\Status\BookingWaitingConfirmation;
use Sdk\Booking\Event\Status\BookingWaitingProcessing;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\BookingStatus;

trait HasStatusesTrait
{
    public function status(): BookingStatus
    {
        return $this->status;
    }

    public function toProcessing(): void
    {
        $this->setStatus(StatusEnum::PROCESSING);
        $this->pushEvent(new BookingProcessing($this));
    }

    public function cancel(): void
    {
        $this->setStatus(StatusEnum::CANCELLED);
        $this->pushEvent(new BookingCancelled($this));
    }

    public function confirm(): void
    {
        $this->setStatus(StatusEnum::CONFIRMED);
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

//        $this->setStatus(StatusEnum::NOT_CONFIRMED);
        $this->status = BookingStatus::createNotConfirmed($reason);
        $this->pushEvent(new BookingNotConfirmed($this, $reason));
    }

    public function toCancelledNoFee(): void
    {
        $this->setStatus(StatusEnum::CANCELLED_NO_FEE);
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
        $this->setStatus(StatusEnum::CANCELLED_FEE);

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
        $this->setStatus(StatusEnum::WAITING_CONFIRMATION);
        $this->pushEvent(new BookingWaitingConfirmation($this));
    }

    public function toWaitingCancellation(): void
    {
        $this->setStatus(StatusEnum::WAITING_CANCELLATION);
        $this->pushEvent(new BookingWaitingCancellation($this));
    }

    public function toWaitingProcessing(): void
    {
        $this->setStatus(StatusEnum::WAITING_PROCESSING);
        $this->pushEvent(new BookingWaitingProcessing($this));
    }

    protected function setStatus(StatusEnum $status): void
    {
        $this->status = BookingStatus::createFromEnum($status);
    }
}
