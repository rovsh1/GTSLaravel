<?php

namespace Module\Hotel\Pricing\Domain\Hotel\Repository;

use DateTimeInterface;
use Module\Hotel\Pricing\Domain\Hotel\Hotel;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\SeasonId;

interface HotelRepositoryInterface
{
    public function findOrFail(HotelId $hotelId): Hotel;

    public function findByRoomId(RoomId $roomId): ?Hotel;

    public function findActiveSeasonId(HotelId $hotelId, DateTimeInterface $date): ?SeasonId;
}
