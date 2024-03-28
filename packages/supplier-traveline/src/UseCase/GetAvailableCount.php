<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Carbon\CarbonPeriod;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;

class GetAvailableCount
{
    public function __construct(
        private readonly RoomQuotaRepository $quotaRepository
    ) {}

    public function execute(int $roomId, CarbonPeriod $period): int
    {
        return $this->quotaRepository->getAvailableCount($roomId, $period);
    }
}
