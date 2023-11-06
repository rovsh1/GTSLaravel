<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Shared\Exception\InvalidStatusTransition;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Shared\Enum\Booking\BookingStatusEnum;

class StatusUpdater
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private readonly BookingUpdater $bookingUpdater,
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

    public function toCancelledNoFee(Booking $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_NO_FEE,
            fn(Booking $booking) => $booking->toCancelledNoFee()
        );
    }

    public function toCancelledFee(Booking $booking, float $supplierPenalty, float|null $clientPenalty = null): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_FEE,
            fn(Booking $booking) => $booking->toCancelledFee($supplierPenalty, $clientPenalty)
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
        $this->bookingUpdater->store($booking);
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
}
