<?php

namespace Module\Shared\Enum;

enum ServiceTypeEnum: int
{
    case CAR_RENT_WITH_DRIVER = 3;
    case CIP_IN_AIRPORT = 2;
    case DAY_CAR_TRIP = 10;
    case HOTEL_BOOKING = 1;
    case INTERCITY_TRANSFER = 9;
    case OTHER = 8;
    case TRANSFER_TO_RAILWAY = 4;
    case TRANSFER_FROM_RAILWAY = 5;
    case TRANSFER_FROM_AIRPORT = 7;
    case TRANSFER_TO_AIRPORT = 6;

    public static function getTransferCases(): array
    {
        return [
            self::CAR_RENT_WITH_DRIVER,
            self::INTERCITY_TRANSFER,
            self::TRANSFER_FROM_AIRPORT,
            self::TRANSFER_TO_AIRPORT,
            self::TRANSFER_FROM_RAILWAY,
            self::TRANSFER_TO_RAILWAY,
            self::DAY_CAR_TRIP,
            self::INTERCITY_TRANSFER,
        ];
    }

    public static function getAirportCases(): array
    {
        return [
            self::CIP_IN_AIRPORT
        ];
    }
}
