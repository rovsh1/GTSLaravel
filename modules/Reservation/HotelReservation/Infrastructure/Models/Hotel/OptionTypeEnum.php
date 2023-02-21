<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Models\Hotel;

enum OptionTypeEnum: int
{
    case PARKING_AVAILABLE = 1;
    case PARKING_SPACE = 2;
    case PARKING_LOCATION = 3;
    case PARKING_RESERV = 4;
    case PARKING_PRICE = 5;
    case CANCEL_FREE_DAYS_PRIOR = 6;
    case CANCEL_GUEST_PAYMENT = 7;
    case CHILDREN_ALLOWED = 8;
    case PETS_ALLOWED = 9;
    case CHECKIN_START_PRESET = 10;
    case CHECKOUT_END_PRESET = 11;
    case TOUR_FEE = 12;
    case VAT = 13;
    case BREAKFAST_TIME = 14;
}
