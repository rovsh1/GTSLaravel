<?php

namespace GTS\Hotel\Domain\Repository;

use Carbon\CarbonInterface;

interface RoomPriceRepositoryInterface
{
    public function updateRoomPrices(int $roomId, int $seasonId, int $rateId, int $guestsNumber, int $type, CarbonInterface $date, float $price);
}
