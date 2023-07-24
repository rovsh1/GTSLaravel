<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Exception\InvalidStatusTransition;
use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

class StatusUpdater
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private readonly BookingUpdater $bookingUpdater,
        private readonly InvoiceCreator $invoiceCreator,
    ) {}

    public function toProcessing(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::PROCESSING,
            fn(BookingInterface $booking) => $booking->toProcessing()
        );
    }

    public function cancel(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED,
            fn(BookingInterface $booking) => $booking->cancel()
        );
    }

    public function confirm(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CONFIRMED,
            fn(BookingInterface $booking) => $booking->confirm()
        );
    }

    public function toNotConfirmed(BookingInterface $booking, string $reason): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::NOT_CONFIRMED,
            fn(BookingInterface $booking) => $booking->toNotConfirmed($reason)
        );
    }

    public function toInvoiced(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::INVOICED,
            fn(BookingInterface $booking) => $booking->toInvoiced()
        );
    }

    public function toPaid(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::PAID,
            fn(BookingInterface $booking) => $booking->toPaid()
        );
        $booking->generateInvoice($this->invoiceCreator);//@todo уточнить у Сергея, в этом ли месте?
    }

    public function toPartiallyPaid(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::PARTIALLY_PAID,
            fn(BookingInterface $booking) => $booking->toPartiallyPaid()
        );
    }

    public function toCancelledNoFee(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_NO_FEE,
            fn(BookingInterface $booking) => $booking->toCancelledNoFee()
        );
    }

    public function toCancelledFee(BookingInterface $booking, float $cancelFeeAmount): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_FEE,
            fn(BookingInterface $booking) => $booking->toCancelledFee($cancelFeeAmount)
        );
    }

    public function toRefundNoFee(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::REFUND_NO_FEE,
            fn(BookingInterface $booking) => $booking->toRefundNoFee()
        );
    }

    public function toRefundFee(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::REFUND_FEE,
            fn(BookingInterface $booking) => $booking->toRefundFee()
        );
    }

    public function toWaitingConfirmation(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::WAITING_CONFIRMATION,
            fn(BookingInterface $booking) => $booking->toWaitingConfirmation()
        );
    }

    public function toWaitingCancellation(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::WAITING_CANCELLATION,
            fn(BookingInterface $booking) => $booking->toWaitingCancellation()
        );
    }

    public function toWaitingProcessing(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::WAITING_PROCESSING,
            fn(BookingInterface $booking) => $booking->toWaitingProcessing()
        );
    }

    /**
     * @param BookingInterface $booking
     * @param BookingStatusEnum $status
     * @param callable $callback
     * @return void
     * @throws InvalidStatusTransition
     */
    public function handleUpdateStatus(BookingInterface $booking, BookingStatusEnum $status, callable $callback): void
    {
        $this->checkCanTransitToStatus($booking, $status);
        $callback($booking);
        $this->bookingUpdater->store($booking);
    }

    /**
     * @param BookingInterface $booking
     * @param BookingStatusEnum $status
     * @return void
     * @throws InvalidStatusTransition
     */
    private function checkCanTransitToStatus(BookingInterface $booking, BookingStatusEnum $status): void
    {
        if (!$this->statusRules->canTransit($booking->status(), $status)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$booking->id()->value()}]]");
        }
    }
}
