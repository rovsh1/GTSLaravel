<?php

namespace GTS\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;
use GTS\Hotel\Domain\Repository\QuotaRepositoryInterface;

class QuotaRepository implements QuotaRepositoryInterface
{
    public function reserveRoomQuotaByDate(int $roomId, CarbonInterface $date, int $quota): void
    {
        // TODO: Implement updateRoomQuotaByDate() method.
    }
}
