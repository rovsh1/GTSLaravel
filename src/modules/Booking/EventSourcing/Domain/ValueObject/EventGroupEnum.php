<?php

namespace Module\Booking\EventSourcing\Domain\ValueObject;

enum EventGroupEnum
{
    case STATUS_UPDATED;
    case PRICE_CHANGED;
    case REQUEST_SENT;

    public static function fromName(string $name): EventGroupEnum
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \Exception("EventEnum [$name] undefined");
    }
}
