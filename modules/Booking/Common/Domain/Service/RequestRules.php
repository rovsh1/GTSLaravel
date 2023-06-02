<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Exception\NotRequestableStatus;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

class RequestRules
{
    /**
     * @var array<int, BookingStatusEnum> $transitions
     */
    protected array $transitions = [];

    public function __construct()
    {
        $this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);
        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::WAITING_CANCELLATION);
        $this->addTransition(BookingStatusEnum::WAITING_PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);
        $this->addTransition(BookingStatusEnum::NOT_CONFIRMED, BookingStatusEnum::WAITING_CONFIRMATION);
    }

    public function isRequestableStatus(BookingStatusEnum $status): bool
    {
        return array_key_exists($status->value, $this->transitions);
    }

    public function canSendCancellationRequest(BookingStatusEnum $status): bool
    {
        return $status === BookingStatusEnum::CONFIRMED;
    }

    public function canSendBookingRequest(BookingStatusEnum $status): bool
    {
        return $status === BookingStatusEnum::PROCESSING;
    }

    public function canSendChangeRequest(BookingStatusEnum $status): bool
    {
        return $this->isRequestableStatus($status)
            && !$this->canSendCancellationRequest($status)
            && !$this->canSendBookingRequest($status);
    }

    /**
     * @param BookingStatusEnum $status
     * @return BookingStatusEnum
     * @throws NotRequestableStatus
     */
    public function getNextStatus(BookingStatusEnum $status): BookingStatusEnum
    {
        $nextStatus = $this->transitions[$status->value] ?? null;
        if ($nextStatus === null) {
            throw new NotRequestableStatus("Status [{$status->value}] not requestable.");
        }
        return $nextStatus;
    }

    private function addTransition(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): void
    {
        $this->transitions[$fromStatus->value] = $toStatus;
    }
}
