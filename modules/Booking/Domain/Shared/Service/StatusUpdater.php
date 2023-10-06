<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Exception\InvalidStatusTransition;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;

class StatusUpdater
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private readonly BookingUpdater $bookingUpdater,
    ) {
    }

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

    public function toCancelledNoFee(BookingInterface $booking): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_NO_FEE,
            fn(BookingInterface $booking) => $booking->toCancelledNoFee()
        );
    }

    public function toCancelledFee(BookingInterface $booking, float $netPenalty, float|null $grossPenalty = null): void
    {
        $this->handleUpdateStatus(
            $booking,
            BookingStatusEnum::CANCELLED_FEE,
            fn(BookingInterface $booking) => $booking->toCancelledFee($netPenalty, $grossPenalty)
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
