<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Domain\Hotel\Service;

use DateTimeInterface;
use Module\Hotel\Pricing\Domain\Hotel\Exception\NotFoundHotelRoomPrice;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\RoomId;

interface BaseDayValueFinderInterface
{
    public function find(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): ?float;

    /**
     * @param RoomId $roomId
     * @param int $rateId
     * @param bool $isResident
     * @param int $guestsCount
     * @param DateTimeInterface $date
     * @return float
     * @throws NotFoundHotelRoomPrice
     */
    public function findOrFail(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): float;
}
