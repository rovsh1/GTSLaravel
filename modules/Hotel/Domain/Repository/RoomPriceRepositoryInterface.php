<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonInterface;

interface RoomPriceRepositoryInterface
{
    public function updateRoomPrices(int $roomId, int $seasonId, int $rateId, int $guestsCount, bool $isResident, CarbonInterface $date, float $price);
}
