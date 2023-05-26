<?php

namespace Module\Shared\Domain\Exception;

enum ErrorCodeEnum: string
{
    case ROOM_NOT_FOUND = 'room_not_found';
    case PRICE_RATE_NOT_FOUND = 'price_rate_not_found';
    case RESERVATION_NOT_FOUND = 'reservation_not_found';
    case UNSUPPORTED_ROOM_GUESTS_NUMBER = 'unsupported_room_guests_number';
    //@todo вроде тоже самое, что и unsupported_room_guests_number, но в другом модуле
    case TOO_MANY_ROOM_GUESTS = 'too_many_room_guests';
}
