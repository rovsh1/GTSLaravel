<?php

namespace Module\HotelOld\Domain\Repository;

interface PriceRateRepositoryInterface
{
    public function existsByRoomAndRate(int $roomId, int $rateId): bool;
}
