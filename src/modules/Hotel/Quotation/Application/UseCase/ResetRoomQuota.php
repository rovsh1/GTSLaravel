<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Service\RoomQuotaUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ResetRoomQuota implements UseCaseInterface
{
    public function __construct(
        private readonly RoomQuotaUpdater $quotaUpdater,
    ) {
    }

    public function execute(int $roomId, CarbonPeriod $period): void
    {
        $this->quotaUpdater->resetRoomQuota($roomId, $period);
    }
}
