<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Exception\CancelFeeRequired;
use Module\Booking\Common\Domain\Exception\InvalidStatusTransition;
use Module\Booking\Common\Domain\Exception\InvalidNotConfirmedReason;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class StatusUpdater
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function toProcessing(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::PROCESSING,
            fn(Booking $booking) => $booking->toProcessing()
        );
    }

    public function cancel(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED,
            fn(Booking $booking) => $booking->cancel()
        );
    }

    public function confirm(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CONFIRMED,
            fn(Booking $booking) => $booking->confirm()
        );
    }

    public function toNotConfirmed(Booking $booking, string $reason): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::NOT_CONFIRMED,
            fn(Booking $booking) => $booking->toNotConfirmed($reason)
        );
    }

    public function toInvoiced(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::INVOICED,
            fn(Booking $booking) => $booking->toInvoiced()
        );
    }

    public function toPaid(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::PAID,
            fn(Booking $booking) => $booking->toPaid()
        );
    }

    public function toPartiallyPaid(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::PARTIALLY_PAID,
            fn(Booking $booking) => $booking->toPartiallyPaid()
        );
    }

    public function toCancelledNoFee(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_NO_FEE,
            fn(Booking $booking) => $booking->toCancelledNoFee()
        );
    }

    public function toCancelledFee(Booking $booking, float $cancelFeeAmount): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_FEE,
            fn(Booking $booking) => $booking->toCancelledFee($cancelFeeAmount)
        );
    }

    public function toRefundNoFee(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::REFUND_NO_FEE,
            fn(Booking $booking) => $booking->toRefundNoFee()
        );
    }

    public function toRefundFee(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::REFUND_FEE,
            fn(Booking $booking) => $booking->toRefundFee()
        );
    }

    public function toWaitingConfirmation(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::WAITING_CONFIRMATION,
            fn(Booking $booking) => $booking->toWaitingConfirmation()
        );
    }

    public function toWaitingCancellation(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::WAITING_CANCELLATION,
            fn(Booking $booking) => $booking->toWaitingCancellation()
        );
    }

    public function toWaitingProcessing(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::WAITING_PROCESSING,
            fn(Booking $booking) => $booking->toWaitingProcessing()
        );
    }

    /**
     * @param Booking $booking
     * @param BookingStatusEnum $status
     * @param callable $callback
     * @return void
     * @throws InvalidStatusTransition
     */
    public function handleUpdateStatus(Booking $booking, BookingStatusEnum $status, callable $callback): void
    {
        $this->checkCanTransitToStatus($booking, $status);
        $callback($booking);
        $this->updateBooking($booking);
    }

    /**
     * @param Booking $booking
     * @param BookingStatusEnum $status
     * @return void
     * @throws InvalidStatusTransition
     */
    private function checkCanTransitToStatus(Booking $booking, BookingStatusEnum $status): void
    {
        if (!$this->statusRules->canTransit($booking->status(), $status)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$booking->id()->value()}]]");
        }
    }

    private function updateBooking(Booking $booking): void
    {
        $this->repository->update($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}
