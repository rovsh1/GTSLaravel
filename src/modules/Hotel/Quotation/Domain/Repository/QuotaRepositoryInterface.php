<?php

namespace Module\Hotel\Quotation\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Domain\ValueObject\BookingId;
use Module\Hotel\Quotation\Domain\ValueObject\BookingPeriod;
use Module\Hotel\Quotation\Domain\ValueObject\RoomId;

interface QuotaRepositoryInterface
{
    public function update(RoomId $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void;

    public function close(RoomId $roomId, CarbonPeriod $period): void;

    public function open(RoomId $roomId, CarbonPeriod $period): void;

    public function reset(RoomId $roomId, CarbonPeriod $period): void;

    public function hasAvailable(RoomId $roomId, BookingPeriod $period, int $count): bool;

    public function book(BookingId $bookingId, RoomId $roomId, BookingPeriod $period, int $count): void;

    public function reserve(BookingId $bookingId, RoomId $roomId, BookingPeriod $period, int $count): void;

    public function cancelBooking(BookingId $bookingId): void;
}
