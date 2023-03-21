<?php

namespace Module\Hotel\Domain\Repository;

interface PriceRateRepositoryInterface
{
    public function existsByRoomAndRate(int $roomId, int $rateId): bool;
}
