<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase\RoomQuota;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Application\Admin\Service\RoomQuotaUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CloseRoomQuota implements UseCaseInterface
{
    public function __construct(
        private readonly RoomQuotaUpdater $quotaUpdater,
    ) {
    }

    public function execute(int $roomId, CarbonPeriod $period): void
    {
        $this->quotaUpdater->closeRoomQuota($roomId, $period);
    }
}
