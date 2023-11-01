<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\UseCase\RoomQuota;

use Carbon\CarbonPeriod;
use Module\Catalog\Application\Admin\Service\RoomQuotaUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateRoomQuota implements UseCaseInterface
{
    public function __construct(
        private readonly RoomQuotaUpdater $quotaUpdater,
    ) {
    }

    public function execute(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays): void
    {
        $this->quotaUpdater->updateRoomQuota(
            $roomId,
            $period,
            $quota,
            $releaseDays
        );
    }
}
