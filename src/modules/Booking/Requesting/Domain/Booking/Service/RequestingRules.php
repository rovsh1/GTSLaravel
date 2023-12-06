<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\Enum\RequestTypeEnum;
use Sdk\Shared\Enum\ServiceTypeEnum;

final class RequestingRules
{
    private Booking $booking;

    /**
     * @var array<int, StatusEnum> $requestableStatuses
     */
    private const REQUESTABLE_STATUSES = [
        StatusEnum::PROCESSING,
        StatusEnum::CONFIRMED,
        StatusEnum::WAITING_PROCESSING,
        StatusEnum::NOT_CONFIRMED,
    ];

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
    }

    /**
     * @return StatusEnum[]
     */
    public static function getRequestableStatuses(): array
    {
        return self::REQUESTABLE_STATUSES;
    }

    public function isBookingRequestable(): bool
    {
        return !$this->isOtherService() && in_array($this->booking->status(), self::REQUESTABLE_STATUSES);
    }

    public function canSendCancellationRequest(): bool
    {
        return !$this->isOtherService() && $this->booking->status() === StatusEnum::CONFIRMED;
    }

    public function canSendBookingRequest(): bool
    {
        return !$this->isOtherService() && $this->booking->status() === StatusEnum::PROCESSING;
    }

    public function canSendChangeRequest(): bool
    {
        return !$this->isOtherService() && in_array($this->booking->status(), [
                StatusEnum::WAITING_PROCESSING,
                StatusEnum::NOT_CONFIRMED
            ]);
    }

    public function getRequestType(): ?RequestTypeEnum
    {
        if ($this->canSendBookingRequest()) {
            return RequestTypeEnum::BOOKING;
        } elseif ($this->canSendCancellationRequest()) {
            return RequestTypeEnum::CANCEL;
        } elseif ($this->canSendChangeRequest()) {
            return RequestTypeEnum::CHANGE;
        } else {
            return null;
        }
    }

    private function isOtherService(): bool
    {
        return $this->booking->serviceType() === ServiceTypeEnum::OTHER_SERVICE;
    }
}
