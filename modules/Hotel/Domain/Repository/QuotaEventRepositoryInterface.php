<?php

namespace Module\Hotel\Domain\Repository;

use Sdk\Module\Support\Facades\DateTimeInterface;

interface QuotaEventRepositoryInterface
{
    public function reserveRoomQuotaByDate(int $roomId, DateTimeInterface $period, int $quota);
}
