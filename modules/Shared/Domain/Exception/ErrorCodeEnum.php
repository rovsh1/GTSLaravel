<?php

namespace Module\Shared\Domain\Exception;

enum ErrorCodeEnum: string
{
    case RoomNotFound = 'room_not_found';
    case PriceRateNotFound = 'price_rate_not_found';
    case ReservationNotFound = 'reservation_not_found';
    case UnsupportedRoomGuestsNumber = 'unsupported_room_guests_number';
}
