<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;

final class RequestRules
{
    /**
     * @var array<int, BookingStatusEnum> $requestableStatuses
     */
    private const REQUESTABLE_STATUSES = [
        BookingStatusEnum::PROCESSING,
        BookingStatusEnum::CONFIRMED,
        BookingStatusEnum::WAITING_PROCESSING,
        BookingStatusEnum::NOT_CONFIRMED,
    ];

    public static function getRequestableStatuses(): array
    {
        return self::REQUESTABLE_STATUSES;
    }

    public function isRequestableStatus(BookingStatusEnum $status): bool
    {
        return in_array($status, self::REQUESTABLE_STATUSES);
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
        return in_array($status, [BookingStatusEnum::WAITING_PROCESSING, BookingStatusEnum::NOT_CONFIRMED]);
    }

    public function getRequestTypeByStatus(BookingStatusEnum $status): RequestTypeEnum
    {
        $requestType = RequestTypeEnum::CHANGE;
        if ($this->canSendBookingRequest($status)) {
            $requestType = RequestTypeEnum::BOOKING;
        }
        if ($this->canSendCancellationRequest($status)) {
            $requestType = RequestTypeEnum::CANCEL;
        }

        return $requestType;
    }
}
