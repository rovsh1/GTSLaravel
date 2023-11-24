<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Module\Booking\Requesting\Domain\BookingRequest\ValueObject\RequestTypeEnum;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;

final class RequestingRules
{
    private Booking $booking;

    /**
     * @var array<int, BookingStatusEnum> $requestableStatuses
     */
    private const REQUESTABLE_STATUSES = [
        BookingStatusEnum::PROCESSING,
        BookingStatusEnum::CONFIRMED,
        BookingStatusEnum::WAITING_PROCESSING,
        BookingStatusEnum::NOT_CONFIRMED,
    ];

    public function booking(Booking $booking): void
    {
        $this->booking = $booking;
    }

    /**
     * @return BookingStatusEnum[]
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
        return !$this->isOtherService() && $this->booking->status() === BookingStatusEnum::CONFIRMED;
    }

    public function canSendBookingRequest(): bool
    {
        return !$this->isOtherService() && $this->booking->status() === BookingStatusEnum::PROCESSING;
    }

    public function canSendChangeRequest(): bool
    {
        return !$this->isOtherService() && in_array($this->booking->status(), [
                BookingStatusEnum::WAITING_PROCESSING,
                BookingStatusEnum::NOT_CONFIRMED
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
