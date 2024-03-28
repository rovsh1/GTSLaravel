<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Carbon\CarbonPeriod;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;

class ReserveQuotas
{
    public function __construct(
        private readonly RoomQuotaRepository $quotaRepository
    ) {}

    public function execute(int $bookingId, int $roomId, CarbonPeriod $period, int $count): void
    {
        $this->quotaRepository->reserveQuotas($bookingId, $roomId, $period, $count);
    }
}
