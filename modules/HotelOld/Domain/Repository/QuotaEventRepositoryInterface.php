<?php

namespace Module\HotelOld\Domain\Repository;

use Custom\Framework\Support\DateTimeInterface;

interface QuotaEventRepositoryInterface
{
    public function reserveRoomQuotaByDate(int $roomId, DateTimeInterface $period, int $quota);
}
