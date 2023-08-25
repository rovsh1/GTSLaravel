<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\TouristId;

interface TouristRepositoryInterface
{
    public function bind(BookingId $bookingId, TouristId $touristId): void;

    public function unbind(BookingId $bookingId, TouristId $touristId): void;
}
