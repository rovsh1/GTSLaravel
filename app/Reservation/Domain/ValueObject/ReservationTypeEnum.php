<?php

namespace GTS\Reservation\Domain\ValueObject;

enum ReservationTypeEnum
{
    case GROUP;
    case HOTEL;
    case TRANSFER;
    case AIRPORT;
    case ADDITIONAL;
}
