<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

enum RoomStatusEnum: int
{
    case WAITING_CONFIRMATION = 1;
    case CANCELLED = 2;
    case CONFIRMED = 3;
}
