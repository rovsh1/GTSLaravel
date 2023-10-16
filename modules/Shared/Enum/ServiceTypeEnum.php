<?php

namespace Module\Shared\Enum;

enum ServiceTypeEnum: int
{
    case HOTEL_BOOKING = 1;
    case CIP_IN_AIRPORT = 2;
    case CAR_RENT = 3;
    case INTERCITY_TRANSFER = 9;
    case TRANSFER_TO_RAILWAY = 4;
    case TRANSFER_FROM_RAILWAY = 5;
    case TRANSFER_TO_AIRPORT = 6;
    case TRANSFER_FROM_AIRPORT = 7;
    case OTHER = 8;

    public static function getTransferCases(): array
    {
        return [
            self::CAR_RENT,
            self::INTERCITY_TRANSFER,
            self::TRANSFER_FROM_AIRPORT,
            self::TRANSFER_TO_AIRPORT,
            self::TRANSFER_FROM_RAILWAY,
            self::TRANSFER_TO_RAILWAY,
        ];
    }

    public static function getAirportCases(): array
    {
        return [
            self::CIP_IN_AIRPORT
        ];
    }
}
