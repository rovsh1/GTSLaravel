<?php

namespace Module\HotelOld\Domain\Repository;

use Carbon\CarbonInterface;

interface RoomPriceRepositoryInterface
{
    public function updateRoomPrices(int $roomId, int $seasonId, int $rateId, int $guestsNumber, bool $isResident, CarbonInterface $date, float $price);
}
