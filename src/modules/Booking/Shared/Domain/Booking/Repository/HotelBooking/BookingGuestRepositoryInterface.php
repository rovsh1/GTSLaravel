<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository\HotelBooking;

use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(AccommodationId $accommodationId, GuestId $guestId): void;

    public function unbind(AccommodationId $accommodationId, GuestId $guestId): void;
}
