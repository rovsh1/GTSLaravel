<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\ValueObject;

enum RequestTypeEnum: int
{
    case BOOKING = 1;
    case CHANGE = 2;
    case CANCEL = 3;
//    case INVOICE = 4;
//    case VOUCHER = 5;
//    case COMBINED_INVOICE = 6;
//    case TRANSFER_BOOKING = 7;
//    case TRANSFER_CHANGE = 8;
//    case TRANSFER_CANCEL = 9;
//    case TRANSFER_INVOICE = 10;
//    case TRANSFER_VOUCHER = 11;
//    case COMBINED_VOUCHER = 12;

//    case AIRPORT_BOOKING = 13;
//    case AIRPORT_CHANGE = 14;
//    case AIRPORT_CANCEL = 15;
//    case AIRPORT_VOUCHER = 16;
//    case AIRPORT_INVOICE = 17;
//    case ADDITIONAL_VOUCHER = 18;
//    case ADDITIONAL_INVOICE = 19;

    //case ADDITIONAL_CANCEL = 20;

    public static function getValues(string $type): array
    {
        return match ($type) {
            'client' => [
//                self::INVOICE,
//                self::VOUCHER,
//                self::TRANSFER_INVOICE,
//                self::COMBINED_INVOICE,
//                self::TRANSFER_VOUCHER,
//                self::COMBINED_VOUCHER,
//                self::AIRPORT_VOUCHER,
//                self::AIRPORT_INVOICE,
//                self::ADDITIONAL_VOUCHER,
//                self::ADDITIONAL_INVOICE,
            ],
            'hotel' => [
                self::BOOKING,
                self::CHANGE,
                self::CANCEL,
            ],
            'transfer' => [
//                self::TRANSFER_BOOKING,
//                self::TRANSFER_CHANGE,
//                self::TRANSFER_CANCEL,
            ],
            'airport' => [
//                self::AIRPORT_BOOKING,
//                self::AIRPORT_CHANGE,
//                self::AIRPORT_CANCEL,
            ],
            'additional' => [
//                self::ADDITIONAL_VOUCHER,
//                self::ADDITIONAL_INVOICE,
            ],
            default => throw new \InvalidArgumentException('Invalid request type')
        };
    }
}
