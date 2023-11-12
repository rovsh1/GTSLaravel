<?php

namespace Module\Booking\EventSourcing\Domain\ValueObject;

enum BookingEventEnum
{
    case STATUS_UPDATED;
    case ATTRIBUTE_MODIFIED;

    public static function fromName(string $name): BookingEventEnum
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \Exception("EventEnum [$name] undefined");
    }
}
