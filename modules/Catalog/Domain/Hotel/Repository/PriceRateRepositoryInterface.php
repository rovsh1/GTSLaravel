<?php

namespace Module\Catalog\Domain\Hotel\Repository;

interface PriceRateRepositoryInterface
{
    public function existsByRoomAndRate(int $roomId, int $rateId): bool;
}
