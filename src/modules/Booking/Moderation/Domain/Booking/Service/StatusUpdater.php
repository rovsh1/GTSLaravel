<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service;

use Module\Booking\Moderation\Domain\Booking\Exception\InvalidStatusTransition;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\AdministratorRules;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\OtherServiceAdministratorRules;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusRulesInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;

class StatusUpdater
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
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
        $statusRules = $this->statusRules;
        if ($booking->serviceType() === ServiceTypeEnum::OTHER_SERVICE) {
            $statusRules = app(OtherServiceAdministratorRules::class);
        }
        $this->checkCanTransitToStatus($statusRules, $booking, $status);
        $callback($booking);
    }

    /**
     * @param Booking $booking
     * @param BookingStatusEnum $status
     * @return void
     * @throws InvalidStatusTransition
     */
    private function checkCanTransitToStatus(
        StatusRulesInterface $statusRules,
        Booking $booking,
        BookingStatusEnum $status
    ): void {
        if (!$statusRules->canTransit($booking->status(), $status)) {
            throw new InvalidStatusTransition("Can't change status for booking [{$booking->id()->value()}]]");
        }
    }
}
