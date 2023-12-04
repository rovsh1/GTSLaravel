<?php

namespace Module\Generic\Notification\Domain\Enum;

enum NotificationTypeEnum
{
    case CUSTOMER_REGISTRATION;
    case CUSTOMER_PASSWORD_RECOVERY;
    case CUSTOMER_DELETED;
    case BOOKING_REQUEST;
    case BOOKING_CHANGE_REQUEST;
    case BOOKING_CANCELLATION_REQUEST;
    case BOOKING_CONFIRMED_BY_HOTEL;
    case BOOKING_MANAGER_ASSIGNED;
    case BOOKING_VOUCHER;
    case BOOKING_INVOICE;

    public static function fromName(string $name): static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \Exception("Enum [$name] not found");
    }
}