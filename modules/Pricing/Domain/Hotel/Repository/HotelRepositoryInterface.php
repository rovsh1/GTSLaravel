<?php

namespace Module\Pricing\Domain\Hotel\Repository;

use DateTimeInterface;
use Module\Pricing\Domain\Hotel\Hotel;
use Module\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Hotel\ValueObject\SeasonId;

interface HotelRepositoryInterface
{
    public function findByRoomId(RoomId $roomId): ?Hotel;

    public function findActiveSeasonId(HotelId $hotelId, DateTimeInterface $date): ?SeasonId;
}
