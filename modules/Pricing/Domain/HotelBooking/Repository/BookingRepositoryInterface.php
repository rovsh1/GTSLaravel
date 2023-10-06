<?php

namespace Module\Pricing\Domain\HotelBooking\Repository;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingId;

interface BookingRepositoryInterface
{
    public function find(int $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;

    public function store(Booking $booking): bool;
}
